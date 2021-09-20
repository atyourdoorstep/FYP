<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\ApiToken;
use App\Models\Privilege;
use App\Models\RolePrivilege;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use function MongoDB\BSON\toJSON;

class UserController extends Controller
{
    public function __construct()
    {
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fName' => ['required','regex:/^[a-zA-Z ]+$/', 'string', 'min:3','max:255','regex:/^[\w-]*$/'],
            'lName' => ['required', 'string', 'min:3', 'max:255','regex:/^[\w-]*$/'],
            'CNIC' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:13', 'unique:users'],
            'contact' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:11', 'unique:users'],
            'address' => [ 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ucreate(Request $request)
    {
       // return $request;
        $data = Validator::make($request->all(),
            [
            'fName' => ['required','regex:/^[a-zA-Z ]+$/', 'string', 'min:3','max:255','regex:/^[\w-]*$/'],
            'lName' => ['required', 'string', 'min:3', 'max:255','regex:/^[\w-]*$/'],
            'CNIC' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:13', 'min:13', 'unique:users'],
            'contact' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:11', 'min:11', 'unique:users'],
            'address' => [ 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]
        );
        if($data->fails())
             return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        return response()->json(
            [
                'success'=>true,
                'user'=>User::create($data)
            ],200
        );
    }

    public function register(Request $request){
        $resp= $this->ucreate($request);
        if(!$resp->isSuccessful())
        {
            return $resp;
        }
        $user=$resp->getData()->user;
        User::find($user->id)->cart()->create([]);
        User::find($user->id)->profile()->create(
            []
        );
        return $this->login($request);
    }
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
        // get the user
          $user = Auth::user();
        $data=['user_id' => Auth::user()->id,
            'token'=>$jwt_token,];
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'user' => $user
        ]);
    }
    public function logout(Request $request)
    {
        if(!User::checkToken($request)){
            return response()->json([
                'message' => 'Token is required',
                'success' => false,
            ],422);
        }
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'exp'=>$exception->getMessage()
            ], 500);
        }
    }
    public function getRole(Request $request)
    {
//        $user=$this->getCurrentUser($request);
//        if(!$user->isSuccessful())
//            return $user;
//        $user=$user->getData()->user;
        $user=$request->all()['user'];
        return response()->json(
            [
                'success'=>true,
                'roleName'=>User::find($user->id)->role->role_name,
//                    $user->role->role_name,
            ]
            ,200
        );
    }
    public function getPrivileges(Request $request)
    {
        if(!User::checkToken($request)){
            return response()->json([
                'message' => 'Token is required',
                'success' => false,
            ],422);
        }
        $resp=$this->getCurrentUser( $request);
        //$user=json_decode($this->getCurrentUser( $request)->content(), true);
        if($resp->getStatusCode()!=200)
        {
            return $resp;
        }
        $user=json_decode($resp->content(), true);
        return  response()->json(
            [
                'success'=>true,
                'privilege'=>DB::select('SELECT privilege_name as privilege from privileges where id in (SELECT privilege_id from role_privileges WHERE role_id ='.$user['user']['role_id'].' )')
            ]
            ,200
        );
    }
    public function getCurrentUser(Request $request){
        if(!User::checkToken($request)){
            return response()->json([
//                'success' => false,
                'message' => 'Token is required'
            ],422);
        }
        //$user = JWTAuth::parseToken()->authenticate();
        $user =null;
        try {
            $user =JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            //400
            return response()->json(['success' => false,
                'message' => 'Token Expired'
            ],403 );
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['success' => false,
                'message' => 'Token Invalid'
            ],400);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500  );
        }
        // add isProfileUpdated....
        $isProfileUpdated=false;
        if($user->isPicUpdated && $user->isEmailUpdated){
            $isProfileUpdated=true;

        }
        $user->isProfileUpdated=$isProfileUpdated;
        return response()->json([
                'success'=>true,
            'user'=>$user,
                ]);
    }
    public function update(Request $request){
//        $user=$this->getCurrentUser($request)->getData();
        $user=$request->all()['user'];
//        if(!$user->success){
//            return response()->json([
//                'success' => false,
//                'message' => 'User is not found'
//            ]);
//        }
//        $user=$user->user;
        $data=$request->all();
        unset($data['token']);
        unset($data['user']);

        $updatedUser = User::where('id', $user->id)->update($data);
        $user =  User::find($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Information has been updated successfully!',
            'user' =>$user
        ]);
    }
    public function findOrFailUser(Request $request)//return user model
    {
        $user=$this->getCurrentUser($request);
        if(!$user->isSuccessful())
            return [
                'success'=>false,
                'message'=>$user->getData()->message,
            ];
//        $user=$user->getData()->user;
//        $user->getData()->user=User::find($user->getData()->user->id);
        return [
            'success'=>true,
            'user'=>User::find($user->getData()->user->id)
        ];
    }
}

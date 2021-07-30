<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
//    //new
//    public function me()
//    {
//        return response()->json($this->guard()->user());
//    }
//    public function logout()
//    {
//        $this->guard()->logout();
//
//        return response()->json(['message' => 'Successfully logged out']);
//    }
//    public function refresh()
//    {
//        return $this->respondWithToken($this->guard()->refresh());
//    }
//    //end new
//    public function guard()
//    {
//        return Auth::guard();
//    }
//    protected function respondWithToken($token)
//    {
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer'
//        ]);
//    }
//    public function mobileLogin(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//        if (auth()->attempt($credentials)) {
//            $token = $request->session()->token();
//            return $this->respondWithToken($token);
//        }
//
//        return response()->json(['error' => 'Unauthorized'], 401);
//    }
//    public function chk($csrf)
//    {
//        return 'done';
//        if($csrf===csrf_token())
//        {
//            return ucfirst($this->Auth::user()->fName);
//        }
//    }
}

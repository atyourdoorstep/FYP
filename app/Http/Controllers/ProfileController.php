<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected string $profilePicPath='1hKpXA8JfkON1MvuSDw9vWhCYQOUsoief';
    protected function create(Request $request)
    {
        // return $request;
        $data = Validator::make($request->all(),
            [
                'user_id' => ['required','number'],
                'title' => [ 'string', 'max:255',],
                'url' => ['url'],
                'description' => [''],
                'image' => [''],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        return response()->json(
            [
                'success'=>true,
                User::create($data)
            ]
            ,200
        );
    }
    public function updateImage(Request $request)//updates profilePicture also deletes old picture if exists
    {
        $user=$request->all()['user'];
        $user=User::find($user->id);
//        $profile= Profile::where('user_id',$user->id)->first();
        $profile= $user->profile;

        if($profile->image) {
            //$url_components = parse_url($id);
            parse_str(parse_url($profile->profileImage())['query'], $params);
            \Storage::disk('google')->delete($params['id']);
        }
        $data = Validator::make($request->all(),
            [
                'image' => 'required',
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $imagePath = $data['image']->store($this->profilePicPath, 'google');
        $url=\Storage::disk('google')->url($imagePath);
         //$data['image']=$url;
         $profile->image=$url;
        $profile->save();
        return response()->json(
            [
                'success'=>true,
//                'profile'=>Profile::find(User::find($user->id)->profile->update(['image'=>$data['image']]))
                'profile'=>$profile,
            ]
            ,200
        );
    }
    public function update(Request $request)
    {
        $user=$request->all()['user'];
        $data = Validator::make($request->all(),
            [
                'title' => [ 'required','string', 'max:255',],
                'url' => ['url'],
                'description' => ['required'],
                'image' => [''],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();

        //User::find($user->id)->profile->update($data);

        //$user=User::find($user->id)->profile->update($data);
        $authUser=User::find($user->id);
        $authUser->profile->update($data);
        return response()->json(
            [
                'success'=>true,
                'profile'=> $authUser->profile,
                'message'=>"Seller's Profile updated",
                ]
            ,200
        );
    }
    public function checkSpeed()
    {
        return User::find(1);
    }
    public function getProfilePicture(Request $request)
    {
        $user=$request->all()['user'];
        return response()->json(
            [
                'success'=>true,
                'url'=>Profile::findOrFail($user->id)->profileImage()
            ],200);
    }
}

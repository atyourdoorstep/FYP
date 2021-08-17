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
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        if(!$user->isSuccessful())
            return $user;
        $user=$user->getData()->user;
        $path='1hKpXA8JfkON1MvuSDw9vWhCYQOUsoief';
        $profile= Profile::where('user_id',$user->id)->first();

        if($id=$profile->profileImage()) {
            $url_components = parse_url($id);
            parse_str($url_components['query'], $params);
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
        $imagePath = $data['image']->store($path, 'google');
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
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        if(!$user->isSuccessful())
            return $user;
        $user=$user->getData()->user;
        $data = Validator::make($request->all(),
            [
                'title' => [ 'required','string', 'max:255',],
                'url' => ['url'],
                'description' => ['required'],
                'image' => [''],
            ]
        );
//        if($data->fails())
//            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();

        //User::find($user->id)->profile->update($data);

        //$user=User::find($user->id)->profile->update($data);
        return response()->json(
            [
                'success'=>true,
                'profile'=>Profile::find(User::find($user->id)->profile->update($data))
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
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        if(!$user->isSuccessful())
            return $user;
        $id=$user->getData()->user->id;
        //return $id;
        return response()->json(
            [
                'success'=>true,
                'url'=>Profile::findOrFail($id)->profileImage()
            ],200);
    }
}

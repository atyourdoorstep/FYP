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
    public function update(Request $request)
    {
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request)->getData()->user;;
        if(!$user->isSuccessful())
            return $user;
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
        if ($data['image'] ?? '') {
            $imagePath = $data['image']->store('uploads/profilePictures', 'public');
            $data['image'] = $imagePath;
        }
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
}

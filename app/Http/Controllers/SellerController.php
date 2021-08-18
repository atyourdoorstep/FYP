<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    public function registerSeller(Request $request)
    {
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        if(!$user->isSuccessful())
            return $user;
        $id=$user->getData()->user->id;
        $data = Validator::make($request->all(),
            [
                'user_name'=>['required','string','max:20'],
                'category_id'=>['required'],
                ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $data['user_id']=$id;
        return response()->json(
            [
                'success'=>true,
                'seller'=>Seller::create($data)
            ]
            ,200
        );
    }
}

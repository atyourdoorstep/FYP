<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerFolder;
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
                'user_name'=>['required','string','max:20','unique:sellers'],
                'category_id'=>['required'],
                ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $data['user_id']=$id;
        if(Seller::where('user_id',$id)->count())
        {
            return response()->json(['success'=>false,'message'=>'This user is already registered as a service provider'],400);
        }
        $seller=Seller::create($data);
        $folder=app('App\Http\Controllers\SellerFolderController')->create($seller->user_name,$seller->id);
        return response()->json(
            [
                'success'=>true,
                'seller'=>$seller,
            ]
            ,200
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SellerDiscountCodeController extends Controller
{
    public function create(Request $request)
    {
        $seller=User::find($request->all()['user']->id)->seller;
        $data =  Validator::make($request->all(),
            [
               'email'=>['required','email'],
                'item_id'=>['required','numeric','min:1'],
                'quantity'=>['required','numeric','min:1'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $user=User::all()->where('email','=',$data['email'])->first();
        $x=Str::random(60);
        $code=substr($x,0,10);
        if(!$user)
            return response()->json(['success'=>false,'message'=>'No user found with this mail'],400);
        else if($seller->user->email==$data['email'])
        {
            return response()->json(['success'=>false,'message'=>'Cannot create a discount for your self'],400);
        }
        else
        {
            return[
                'user'=>$user,
                'code'=>$code,
            ];
        }


//        return [
//            'code'=>$x,
//            'sub'=>substr($x,0,10),
//        ];
    }
}

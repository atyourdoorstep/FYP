<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\DiscountCodeItem;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DiscountCodeController extends Controller
{
    public function create(Request $request)
    {
        $seller=User::find($request->all()['user']->id)->seller;
        $items=$request->all()['items'];
        $data =  Validator::make($request->all(),
            [
               'email'=>['required','email'],
                'items_list'=>['required'],
                'items_list.item_id'=>['required','numeric','min:1'],
                'items_list.quantity'=>['required','numeric','min:1'],
                'items_list.discount'=>['required','numeric','min:1'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $user=User::all()->where('email','=',$data['email'])->first();
        $x=Str::random(10);
        $code=$x;
        $code=substr($x,0,10);
        if(!$user)
            return response()->json(['success'=>false,'message'=>'No user found with this mail'],400);
        else if($seller->user->email==$data['email'])
        {
            return response()->json(['success'=>false,'message'=>'Cannot create a discount for your self'],400);
        }
//        else
//        {
//            return[
//                'user'=>$user,
//                'code'=>$code,
//            ];
//        }
        $dc=DiscountCode::create(
            [
                'seller_id'=>$seller->id,
                'user_id'=>$user->id,
            ]
        );
        foreach ($items as $item)
        {
            $dci=DiscountCodeItem::create(
              [
                  'item_id'=>$item['item_id'],
                  'discount'=>$item['discount']??0,
                  'quantity'=>$item['quantity'],
                  'discount_code_id'=>$item['item_id'],$dc->id,
              ]
            );
        }
//        return [
//            'code'=>$x,
//            'sub'=>substr($x,0,10),
//        ];
    }
}

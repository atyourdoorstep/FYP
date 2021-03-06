<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\DiscountCodeItem;
use App\Models\Seller;
use App\Models\User;
use Google\Exception;
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
                'items'=>['required'],
                "items.*.item_id"=>['required','numeric','min:1'],
                "items.*.quantity"=>['required','numeric','min:1'],
                "items.*.discount"=>['required','numeric','min:1'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $user=User::all()->where('email','=',$data['email'])->first();
        $code='';
        do {
            $code = Str::random(10);
        }while(DiscountCode::all()->where('code',$code)->count()>0);
//        $code=substr($x,0,10);
        if(!$user)
            return response()->json(['success'=>false,'message'=>'No user found with this mail'],400);
        else if($seller->user->email==$data['email'])
        {
            return response()->json(['success'=>false,'message'=>'Cannot create a discount for your self'],400);
        }
        $dc=DiscountCode::create(
            [
                'seller_id'=>$seller->id,
                'user_id'=>$user->id,
                'code'=>$code,
            ]
        );
        foreach ($items as $item)
        {
            $dci=DiscountCodeItem::create(
              [
                  'item_id'=>$item['item_id'],
                  'discount'=>$item['discount']??0,
                  'quantity'=>$item['quantity'],
                  'discount_code_id'=>$dc->id,
              ]
            );
        }
        return response()->json(
          [
              'success'=>true,
              'discount'=>DiscountCode::with('discountCodeItems')->where('id',$dc->id)->get(),
          ]  ,
            200
        );
    }
    public function getDiscount(Request $request)
    {
        $user=$request->all()['user'];
        $code=$request->all()['code'];
        $dc=DiscountCode::all()->where('code',$code);
        if(!$dc->count())
            return response()->json(['success'=>false,'message'=>'no such discount code found'],400);
        $dc=$dc->first();
        if($dc->user_id!=$user->id)
            return response()->json(['success'=>false,'message'=>'this discount code is not for you'],400);
        $dc->discountCodeItems;
        return $dc;
    }
    public function destroy(Request $request)
    {
        $user=$request->all()['user'];
        $code=$request->all()['code'];
        $dc=DiscountCode::all()->where('code',$code);
        if(!$dc->count())
            return response()->json(['success'=>false,'message'=>'no such discount code found'],400);
        $dc=$dc->first();
        if($dc->user_id!=$user->id)
            return response()->json(['success'=>false,'message'=>'this discount code is not for you'],400);
        try {
            $dc->discountCodeItems()->delete();
            $dc->delete();
        }
        catch (Exception $exception)
        {
            return response()->json(['success'=>false,'message'=>'Error: '.$exception->getMessage()],400);
        }
        return response()->json(['success'=>true,'message'=>'code destroyed'],200);
    }
}

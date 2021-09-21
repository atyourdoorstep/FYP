<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user=$request->all()['user'];
        $data =  Validator::make($request->all(),
            [
                'item_id'=>['required','numeric'],
                'quantity'=>['required','numeric','min:1'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $user=User::find($user->id);

        if($user->cart->cartItems->where('item_id',$data['item_id'])->count())
        {
            $ci=$user->cart->cartItems->where('item_id',$data['item_id'])->first();
            $ci->quantity=$ci->quantity+$data['quantity'];
            $ci->update();
        }
        else {
            $user->cart->cartItems()->create($data);
        }
        return $this->getCart($request);
    }
    public function getCart(Request $request)
    {
        $user=$request->all()['user'];
        return
            [
                'success'=>true,
                'cart'=>Cart::with('cartItems.item')->where('user_id',$user->id)->first(),
            ];
    }
    public function removeFromCart(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $data =  Validator::make($request->all(),
            [
                'id'=>['required','numeric'],
                'quantity'=>[''],
            ]
        );
        $data=$request->all();
        $ci=CartItem::find($data['id']);
        if($data['quantity']??'')
        {
            if($data['quantity']>=$ci->quantity)
            {
                return
                    [
                        'success'=>false,
                        'message'=>'Please enter quantity less then '.CartItem::find($data['id'])->quantity,
                    ];
            }
            else
            {
                $ci->quantity= $ci->quantity-$data['quantity'];
                $ci->update();
            }
        }
        else
        {
            $ci->delete();
        }
        return $this->getCart($request);
    }
}

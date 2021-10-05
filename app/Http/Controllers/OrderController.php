<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user=User::find($request->all()['user']->id);
//        $seller=Seller::find($request->all()['seller_id']);
//        return $request->all();
        $items=$request->all()['items'];
        $order=Order::create(
            [
                'user_id'=>$user->id,
                'status'=>'processing',
            ]
        );
        foreach ($items as $item)
        {
            OrderItem::create(
                [
                    'item_id'=>$item['item_id'],
                    'order_id'=>$order->id,
                    'quantity'=>$item['quantity'],
                    'seller_id'=>Item::find($item['item_id'])->seller->id,
                ]
            );
        }
        return Order::with('orderItems')->where('id',$order->id)->get();
    }
    public function getOrders(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $check=$request->all()['check']??false;
        if($check)
        {
            return OrderItem::where('seller_id','=',$user->seller->id)->get();
        }
        $orders=Order::with('orderItems')->where('user_id',$user->id)->get();
        return response()->json(
            [
                'processing'=>$orders->where('status','=','processing'),
                'delivered'=>$orders->where('status','=','delivered'),
                'canceled'=>$orders->where('status','=','canceled'),
                'completed'=>$orders->where('status','=','completed'),
            ]
        );
    }
}

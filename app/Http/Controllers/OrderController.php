<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
        foreach ($items as $item) {
            $it=$user->seller->items->where('id', '=', $item['item_id']);
            if ($it->count()) {
                return response()->json(['success' => false, 'message' => 'You cannot Order you own item: '.$it->first()->name], 400);
            }
        }
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
            return response()->json(
                [User::with(
                [
                'orders.orderItems'=>fn($query)=> $query->with('item')->where('order_items.seller_id', $user->seller->id)
                ]
        )->whereIn('id',
                Arr::pluck(DB::table('orders')
                    ->select('user_id')
                    ->whereIn('id',
                        Arr::pluck(DB::table('order_items')
                            ->select('order_id')
                            ->where('seller_id', $user->seller->id)
                            ->get(), 'order_id')
                    )
                    ->get(), 'user_id')
            )->get()
                ]
            );
        }
        $orders=Order::with('orderItems.item')->where('user_id',$user->id)->orderBy('created_at')->get();
//        return $orders;
//        $can=OrderItem::whereIn('order_id',Arr::pluck(DB::table('orders')
//            ->select('id')
//            ->where('user_id', $user->id)
//            ->get(), 'id'))->get();
        return response()->json(
            [
                'orders'=>$orders,
//                'canceled'=>$can->where('status','=','canceled'),
            ]
        );
    }
}
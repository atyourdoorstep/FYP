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
use Illuminate\Support\Facades\Validator;

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
    public function changeStatus(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $data =  Validator::make($request->all(),
            [
                'order_item_id' => ['required','numeric'],
                'order_id' => ['required','numeric'],
                'status'=> ['required','string','max:30','min:3'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        if($request->all()['seller']??'')
        {

//            $oi=OrderItem::find($data['order_item_id'])->where('seller_id',$user->seller->id);//->where('order_id',$data['order_id']);
            $oi=$user->seller->orderItems->where('order_id',$data['order_id'])->where('id',$data['order_item_id'])->first();
            $oi=OrderItem::find($oi->id);
            if($oi->status=='canceled')
            {
                return response()->json(['success'=>false,'message'=>'This order has been canceled'],400);
            }
            $oi->status=$data['status'];
            $oi->save();
            return [
                'order_item'=>$oi,
            ];
        }else {
            $itemId = $request->all()['order_item_id'];
            $oid = $request->all()['order_id'];
//            $a->orders->where('id', 10)->first()->orderItems->where('id', $itemId)->first()
            $order = $user->orders->where('id', $oid)->first()->orderItems->where('id', $itemId)->first();
//            return [
//            'order'=> $user->orders->where('id', $oid)->first()->orderItems->where('id', $itemId)->first(),
//                'oid'=>$oid,
//                'iid'=>$itemId,
//            ];
            $oi=OrderItem::find($order->id);
            if($oi->status=='shipped')
            {
                return response()->json(['success'=>false,'message'=>'This order has been shipped you can not cancel it now'],400);
            }
            $oi->status=$data['status'];
            $oi->save();
            return [
                'order_item'=>OrderItem::with('item')->where('id',$oi->id)->get(),
            ];
        }

    }
    public function getOrders(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $check=$request->all()['check']??false;
        DB::enableQueryLog();
        if($check)
        {
//            $orderItemIdList=Arr::pluck(DB::table('order_items')
//                ->select('id')
//                ->where('seller_id', $user->seller->id)
//                ->get(), 'id');
            $orderItemIdList=Arr::pluck($user->seller->orderItems, 'id');
            $orderIdList= Arr::pluck($user->seller->orderItems , 'order_id');
            $orderUserIdList=Arr::pluck(DB::table('orders')
                ->select('user_id')
                ->whereIn('id',$orderIdList)
                ->get(), 'user_id');
            return response()->json(
                [
                    User::with(
                [
                'orders.orderItems'=>fn($query)=> $query->with('item')->whereIn('order_items.id', $orderItemIdList)->get()
                ]
        )->whereIn('id',$orderUserIdList)->get(),
                'query'=>DB::getQueryLog(),
                'seller'=>$user->seller,
                ]
            );
        }
        $orders=Order::with('orderItems.item')->where('user_id',$user->id)->orderBy('created_at')->get();
        return response()->json(
            [
                'orders'=>$orders,
//                'canceled'=>$can->where('status','=','canceled'),
            ]
        );
//        {
//            $orderItemsList= Arr::pluck(DB::table('order_items')
//                ->select('order_id')
//                ->where('seller_id', $user->seller->id)
//                ->get(), 'order_id');
//            return response()->json(
//
//                [
//
//                    User::with(
//                        [
//                            'orders.orderItems'=>fn($query)=> $query->with('item')->where('order_items.seller_id', $user->seller->id)
//                        ]
//                    )->whereIn('id',
//                        Arr::pluck(DB::table('orders')
//                            ->select('user_id')
//                            ->whereIn('id',
//                                Arr::pluck(DB::table('order_items')
//                                    ->select('order_id')
//                                    ->where('seller_id', $user->seller->id)
//                                    ->get(), 'order_id')
//                            )
//                            ->get(), 'user_id')
//                    )->get(),
//
//                    'query'=>DB::getQueryLog(),
//                    'seller'=>$user->seller,
//                ]
//            );
//        }
    }
}

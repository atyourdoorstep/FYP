<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request)
    {
        $user=$request->all()['user'];
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $orderItems=$request->all()['order_items'];
//        return OrderItem::all()->whereIn('id',$orderItems)->where('seller_id',$user->seller->id);
        $data =  Validator::make($request->all(),
            [
                'seller_id' => ['required','numeric'],
                'user_id' => ['required','numeric'],
                'order_items' => ['required'],
                'discount' => ['','numeric','min:0'],
                'remarks' => ['string','nullable'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $orderItems=OrderItem::all()->whereIn('id',$orderItems)->where('seller_id',$user->seller->id);
        $invoice=Invoice::create(
            [
                'seller_id'=>$user->seller->id,
                'user_id'=>$data['user_id'],
                'remarks'=>$data['remarks']??null,
                'discount'=>$data['discount']??0,
            ]
        );
        foreach ($orderItems as $item)
        {
            $item->status='confirmed';
            $item->update();
            InvoiceItem::create(
                [
                    'item_id'=>$item->item_id,
                    'invoice_id'=>$invoice->id,
                    'quantity'=>$item['quantity'],
                ]
            );
        }
        return Invoice::with('invoiceItems.item')->where('id',$invoice->id)->get();
    }
}

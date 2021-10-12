<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request)
    {
        $user=$request->all()['user'];
        $orderItems=$request->all()['order_items'];
        $data =  Validator::make($request->all(),
            [
                'seller_id' => ['required','numeric'],
                'user_id' => ['required','numeric'],
                'discount' => ['required','numeric','min:0'],
                'remarks' => ['string','nullable'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $invoice=Invoice::create(
            [
                'seller_id'=>$user->seller->id,
                'user_id'=>$data['user_id'],
                'remarks'=>$data['remarks'],
                'discount'=>$data['discount'],
            ]
        );
        foreach ($orderItems as $item)
        {
            $oi=OrderItem::find($item);

            InvoiceItem::create(
                [
                    'item_id'=>$oi->item_id,
                    'invoice_id'=>$invoice->id,
                    'quantity'=>$item['quantity'],
                ]
            );
        }
        return Invoice::with('orderItems.item')->where('id',$invoice->id)->get();
    }
}

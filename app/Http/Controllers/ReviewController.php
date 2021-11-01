<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Review;
use Google\Service\ToolResults\Any;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $cr=$this->canRate($request);
//        return $cr;
        if(!$cr->getData()->success)
        {
            return $cr;
        }
        $user=$request->all()['user'];
        $data =  Validator::make($request->all(),
            [
                'item_id' => ['required','numeric'],
                'review' => 'string',
                'review_id' => 'numeric',
                'rating' => ['required','numeric','min:1'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();

        return response()->json(
            [
                'success'=>true,
                'review'=>Review::create(
                    [
                        'user_id'=>$user->id,
                        'item_id'=>$data['item_id'],
                        'review'=>$data['review'],
                        'review_id'=>$data['review_id']??null,
                        'rating'=>$data['rating'],
                    ]
                ),
            ]
        );
    }
    public function canRate(Request $request)
    {
        $user=$request->all()['user'];
        $data =  Validator::make($request->all(),
            [
                'item_id' => ['required','numeric'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $re=Review::where('user_id',$user->id)->where('item_id',$data['item_id'])->get();
        $item=Item::find($data['item_id']);
        if($re->count())
        {
            return response()->json([
                'success'=>false,
                'message'=> 'You have already rated this ' . (($item->type == 'service') ? 'service' : 'product') . ' thanks for your feedback',
            ]);
        }
        foreach ($item->orderItems as $orderItem)
        {
            if($orderItem->status=='completed'&&$orderItem->order->user_id==$user->id)
            {
                return response()->json(['success'=>true,]);
            }
        }
        return response()->json([
            'success'=>false,
            'message'=> 'You have to ' . (($item->type == 'service') ? 'avail' : 'buy') . ' this ' . (($item->type == 'service') ? 'service' : 'product').' to rate give your reviews',
            ]);
    }
}

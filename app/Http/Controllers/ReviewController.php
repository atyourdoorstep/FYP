<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
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
}

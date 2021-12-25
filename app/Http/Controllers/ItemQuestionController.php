<?php

namespace App\Http\Controllers;

use App\Models\ItemQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemQuestionController extends Controller
{
    public function create(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        $data =  Validator::make($request->all(),
            [
                'message' => ['required',''],
                'is_public' => ['required','max:1','min:0'],
                'item_id'=> ['required','numeric'],
                'item_questions_id'=> ['nullable','numeric'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $data['user_id']=$user->id;
//        return $data;

        return response()->json(
            [
                'success'=>true,
                'question'=>ItemQuestion::create($data),
            ]
        );
    }
}

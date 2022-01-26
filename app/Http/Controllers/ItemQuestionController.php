<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        if($data['item_questions_id']??false)
        {
            if(Item::find($data['item_id'])->seller->user->id!=$user->id)
            {
                return response()->json(
                    [
                        'success'=>false,
                        'message'=>'Only Seller of this Item can reply to this thread.'
                    ]
            );
            }
        }
        $data['user_id']=$user->id;
//        return $data;

        return response()->json(
            [
                'success'=>true,
                'question'=>ItemQuestion::create($data),
            ]
        );
    }

    public function getItemQuestions(Request $request)
    {
//        $user=User::find($request->all()['user']->id);
        $data =  Validator::make($request->all(),
            [
                'item_id'=> ['required','numeric'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        if($data['token']??false)
        {
            $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
//            return $user->getData()->user;
            $user=$user->getData()->user;
            DB::enableQueryLog();
            if(Item::find($data['item_id'])->seller->user->id==$user->id)
            {
                return response()->json(
                    [
                        'success'=>true,
                        'itemQuestions'=>ItemQuestion::with('childQuestions')->where('item_id','=',$data['item_id'])
                            ->whereNull('item_questions_id')->get(),
//                        'query'=>DB::getQueryLog(),
                    ]
                );
            }
            else
            {
               $ids=Arr::pluck(ItemQuestion::where('item_id','=',$data['item_id'])->where('is_public','=','1')->get(), 'id');
//               return $ids;
                array_push($ids,Arr::pluck(ItemQuestion::where('user_id','=',$user->id)->where('item_id','=',$data['item_id'])->get(), 'id'));
                //$ids=Arr::pluck(ItemQuestion::where('user_id','=',$user->id)->where('item_id','=',$data['item_id']), 'id');
//                return $ids;
                return response()->json(
                    [
                        'success'=>true,
                        'itemQuestions'=>ItemQuestion::whereIn('id',$ids),
//                        'itemQuestions'=>ItemQuestion::with('childQuestions')->where('item_id','=',$data['item_id'])
//                            ->whereNull('item_questions_id')
//                            ->where('is_public','=','1')
//                            ->orWhere('user_id','=',$user->id)->get(),

                    ]
                );
            }
        }
        return response()->json(
        [
            'success'=>true,
            'itemQuestions'=>ItemQuestion::with('childQuestions')->where('item_id','=',$data['item_id'])
                ->whereNull('item_questions_id')
                ->where('is_public','=','1')->get(),
        ]
    );
    }
}

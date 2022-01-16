<?php

namespace App\Http\Controllers;

use App\Models\FavouriteCategory;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFavouriteController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $user=$request->all()['user'];

        return response()->json( [
            'success'=>true,
            'UserFavourite'=>UserFavourite::create(
                [
                'user_id'=>$user->id,
                    ]
            )
        ]
        );
    }
    public function addFavourite(Request $request)
    {
        $user=$request->all()['user'];
        $data =  Validator::make($request->all(),
            [
                'category_id' => ['required','numeric',]
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $a=UserFavourite::where('user_id','=',$user->id)->get()->first();
        if(!$a)
        {
            $a=$this->create($request);
        }
        $temp=FavouriteCategory::where('user_favourites_id','=',$a->id)->where('category_id','=',$data['category_id'])->get();
        if(!count($temp))
        {
            return response()->json( [
                    'success'=>true,
                    'FavouriteCategory'=>FavouriteCategory::create(
                        [
                            'user_favourites_id'=>$a->id,
                            'category_id'=>$data['category_id'],
                        ]
                    )
                ]
            );
        }
        return response()->json( [
                'success'=>true,
                'FavouriteCategory'=>$temp->first()
            ]
        );
    }

}

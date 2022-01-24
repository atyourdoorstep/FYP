<?php

namespace App\Http\Controllers;

use App\Models\FavouriteCategory;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
//            return $this->create($request)->content();
            $a=json_decode($this->create($request)->content(), true);
            $a=$a['UserFavourite'];
//            $a=$this->create($request);
//            $a=UserFavourite::where('user_id','=',$user->id)->get()->first();
        }
        $temp=FavouriteCategory::where('user_favourites_id','=',$a['id'])->where('category_id','=',$data['category_id'])->get();
        if(!count($temp))
        {
            return response()->json( [
                    'success'=>true,
                    'FavouriteCategory'=>FavouriteCategory::create(
                        [
                            'user_favourites_id'=>$a['id'],
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
    public function topSoldToday(Request $request)
    {
//        $countItem=DB::select('select i1.id,i1.name,(select count(i2.item_id) from order_items i2 where i2.item_id=i1.id) as count from items i1 order by count desc');
        $countItem=DB::select("select i1.id,(select count(i2.item_id) from order_items i2 where i2.item_id=i1.id) as count from items i1 order by count desc");
        $countItem=Arr::pluck($countItem,'id');
        $countItem=array_slice($countItem,0,8,true);
//        $countItem=Item::withAvg('reviews','rating')->whereIn('id',$countItem)->get();
        $countItem=Item::with('reviews.user')->withAvg('reviews','rating')->whereIn('id',$countItem)->get();
        return $countItem;
    }
}

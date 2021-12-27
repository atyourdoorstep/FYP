<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Seller;
use App\Models\SellerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function searchSeller(Request $request)
    {
        return [
            'search'=> $request->search,
            'result'=>Seller::where('user_name', 'LIKE',"%". $request->search . "%")->get(),
        ];
    }
    public function searchCat(Request $request)
    {
        return [
            'result'=>Category::where('name', 'LIKE',"%". $request->search . "%")->get(),
        ];
    }
    public function searchItem(Request $request)
    {
        return [
            'result'=>Item::with(['reviews.user'])->where('name', 'LIKE',  "%".$request->search . "%")->get(),
        ];
    }
    public function itemInRange(Request  $request)
    {
        $addresses=$this->searchSellerInArea($request);
        $data =  Validator::make($request->all(),
            [
                'name'=>['required']
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $addresses=Arr::pluck($addresses, 'seller_id');
        return response()->json(
            [
            'result'=>Item::with(['seller.sellerAddress','reviews.user'])->where('name', 'LIKE',  "%".$data['name'] . "%")->whereIn('seller_id',$addresses)->get(),
        ]
        );
    }

    public function searchSellerInArea(Request  $request)
    {
        //$latitude, $longitude, $radius = 400
        $data =  Validator::make($request->all(),
            [
                'lat' => ['required',''],
                'long' => ['required',''],
                'radius'=> ['required','numeric'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $activeSellerList=Arr::pluck(Seller::all()->where('is_active','=','1'), 'id');
//        return $activeSellerList;
        $address = SellerAddress::selectRaw("*,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$data['lat'],$data['long'], $data['lat']])
            ->whereIn('seller_id',$activeSellerList )
            ->having("distance", "<", $data['radius'])
            ->orderBy("distance",'asc')
            ->offset(0)
            ->limit(20)
            ->get();

//        $address = DB::select("SELECT (3956 * 2 * ASIN(SQRT( POWER(SIN((". $data['lat'] ."- lat) *  pi()/180 / 2), 2) + COS(". $data['lat'] ."* pi()/180) * COS(lat * pi()/180) * POWER(SIN((". $data['long'] ."- long) * pi()/180 / 2), 2) ))) as distance, lat, long FROM seller_addresses HAVING distance <= 30");
        return $address;
    }
}

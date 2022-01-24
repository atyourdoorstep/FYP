<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Review;
use App\Models\Seller;
use App\Models\SellerFolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function create(Request $request)//regItem
    {
        $user=$request->all()['user'];
        $user=User::find($user->id);
        if(!Seller::where('user_id',$user->id)->count())
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'User is not registered as a Service Provider Register as a service provider to start selling',

                ]
                ,400
            );
        }
        $data =  Validator::make($request->all(),
            [
                'name' => ['required'],
                'description' => 'nullable',
                'type' => 'string',
                'category_id' => 'required',
                'image' => 'required',
                'price' => ['required','numeric','min:1'],
                'isBargainAble'=>'required'
            ]
        );
        //The name has already been taken.
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $seller=$user->seller;
//        if(Item::where('name',$data['name'])->where('seller_id',$seller->id)->get()->count())
        if($seller->items->where('name',$data['name'])->where('category_id',$data['category_id'])->count())
        {
            return response()->json(['success'=>false,'message'=>'You have already added item with same name('.$data['name'].') in '.Category::find($data['category_id'])->name.' category'],400);
        }
        $data['seller_id']=$seller->id;
        $imagePath = $data['image']->store($seller->sellerFolder['item'], 'google');
        $url=\Storage::disk('google')->url($imagePath);
        $data['image']=$url;
        return response()->json(
            [
                'success'=>true,
                'item'=>Item::create($data),
            ]
            ,200
        );
    }
    public function update(Request $request)//regItem
    {
        $user=$request->all()['user'];
        if(!Seller::where('user_id',$user->id)->count())
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'User is not registered as a Service Provider Register as a service provider to start selling',
                ]
                ,400
            );
        }
        $data =  Validator::make($request->all(),
            [
                'name' => [''],
                'description' => 'nullable',
                'type' => 'string',
                'category_id' => '',
                'image' => '',
                'id' =>['required','numeric','min:1'],
                'price' => 'numeric',
                'inStock'=>['numeric','min:0','max:1'],
                'isBargainAble'=>['numeric','min:0','max:1']
            ]
        );
        //The name has already been taken.
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $item=Item::findOrFail($request->all()['id']);
        $data=$request->all();
        $seller=User::find($user->id)->seller;
        if($data['name']??'') {
            if (Item::where('name',$data['name'])->where('seller_id', '!=',$seller->id)->where('id',$item->id)->get()->count()) {
                return response()->json([ 'success' => false, 'message' => 'The name has already been taken.'], 400);
            }
        }
        if($data['image']??'') {
            $imagePath = $data['image']->store($seller->sellerFolder['item'], 'google');
            $url = \Storage::disk('google')->url($imagePath);
            $data['image'] = $url;
            parse_str(parse_url($item->image)['query'], $params);
            \Storage::disk('google')->delete($params['id']);
        }
        $item->update($data);
        return response()->json(
            [
                'success'=>true,
                'item'=>Item::find($item->id),
            ]
            ,200
        );
    }
    public function getRating($item)
    {
        $reviews = $item->reviews;
        $reviews=$reviews??'';
        return
            [
                'total' => $reviews->count(),
                '5' => $reviews->where('rating', 5)->count(),
                '4' => $reviews->where('rating', 4)->count(),
                '3' => $reviews->where('rating', 3)->count(),
                '2' => $reviews->where('rating', 2)->count(),
                '1' => $reviews->where('rating', 1)->count(),
            ];
    }
    public function getItem(Request $request)
    {
        if($request->item_id??'')
            return Item::with(['reviews.user','seller'])->where('id', '=',  $request->item_id??'' )->get();
        return '';
    }
}

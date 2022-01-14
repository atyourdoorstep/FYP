<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{


    public function catIndex($parent =null)
    {
        if ($parent ?? '') {
            return (view('admin.catIndex',
                [
                    'parent'=> Category::find($parent),
                    'data' => Category::with('children')->where('category_id',$parent)->paginate(10),
                ]
            ));

        }
        return (view('admin.catIndex', ['data' => Category::with('children')->whereNull('category_id')->paginate(10)]));
    }
    public function sellerList(Request $request)
    {
        return (view('admin.sellerList', ['data' => Seller::paginate(10)]));
//        return (view('admin.sellerList'));
    }
    public function changeSellerStatus(Request $request)
    {
        $data =  Validator::make($request->all(),
            [
                'seller_id' => ['required','numeric'],
                'user_id' => ['required','numeric'],
                'admin_id' => ['required','numeric'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $user=User::find($data['user_id']);
        if(!count($user->appAdmin)||$user->appAdmin[0]->id!=$data['admin_id'])
        {
            return response()->json(
              [
                  'success'=>false,
                  'massage'=>'Only admin can use this Request',
              ]
            );
        }

        //The name has already been taken.

        $seller = Seller::find($data['seller_id']);
        $seller->is_active = !$seller->is_active;
        $seller->push();
        return response()->json(
        [
            'success'=>true,
            'massage'=>'Status changed',
        ]
    );
    }
}

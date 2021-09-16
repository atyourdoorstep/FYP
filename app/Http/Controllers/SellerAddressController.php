<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerAddressController extends Controller
{
    public function create(Request $request)
    {
//        'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],             'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']

        $user=$request->all()['user'];
//        $user=Seller::where('user_id',$user->id)->get();
//        $user=Seller::where('user_id',$user->id)->get();
        $request->merge(['seller_id' =>User::find($user->id)->seller->id]);
//        return $request;
        $data = Validator::make($request->all(),
            [
                'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'seller_id'=>['required','unique:seller_addresses']
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        return [
            'success'=>true,
            'address'=>SellerAddress::create([
                'lat'=>$data['lat'],
                'long'=>$data['long'],
                'name'=>$data['name'],
                'seller_id'=> $data['seller_id'],
            ]),
        ];
    }
    public function update(Request $request)
    {
        $user=$request->all()['user'];


    }
}

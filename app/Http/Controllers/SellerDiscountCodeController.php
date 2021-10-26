<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SellerDiscountCodeController extends Controller
{
    public function create(Request $request)
    {
        $data =  Validator::make($request->all(),
            [
               'email'=>['required','email'],
                'item_id'=>['required','numeric','min:1'],
                'quantity'=>['required','numeric','min:1'],
            ]
        );
        $x=Str::random(60);
        return [
            'code'=>$x,
            'sub'=>substr($x,0,10),
        ];
    }
}

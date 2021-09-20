<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function create(Request $request)
    {
        $user=$request->all()['user'];
        $user=User::find($user->id);
        Cart::create(
          [
              'user_id'=>$user->id,
          ]
        );
        return  $user->cart;
    }
}

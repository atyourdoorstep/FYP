<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function getWallet(Request $request)
    {
        $user=User::find($request->all()['user']->id);
        if($user->role_id==1)
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'This user is not registered as a seller'
                ]
            );
        }
        return response()->json(
            [
                'success'=>true,
                'wallet'=>$user->seller->wallet,
            ]
        );
    }
}

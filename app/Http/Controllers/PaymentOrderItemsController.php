<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentOrderItemsController extends Controller
{
    public function getStripToken(Request $request)
    {
        $data =  Validator::make($request->all(),
            [
                'number' => ['required','numeric'],
                'exp_month' => ['required','numeric','min:1','max:12'],
                'cvc' => ['required','numeric'],
                'exp_year' => ['required','numeric','min:2021'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $stripe = \Cartalyst\Stripe\Stripe::make(env('STRIPE_SECRET'));
        $token = '';
        try{
            $token = $stripe->tokens()->create([
                'card' => [
                    'number'    => $request->all()['number'],
                    'exp_month' => $request->all()['exp_month'],
                    'cvc'       => $request->all()['cvc'],
                    'exp_year'  => $request->all()['exp_year'],
                ],
            ]);
            return response()->json( [
                'success'=>true,
                'token'=>$token,
            ]);
        }catch (Exception $exception)
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>$exception->getMessage(),
                ]
            );
        }
    }
}

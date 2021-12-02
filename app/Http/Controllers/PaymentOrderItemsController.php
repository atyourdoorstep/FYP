<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrderItem;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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

    public function paymentHistory(Request $request)
    {
        $user=$request->all()['user'];
//        DB::enableQueryLog();
        $user=User::find($user->id);
        $idList=Arr::pluck($user->orders, 'id');
//        $t=PaymentOrderItem::whereIn('order_id',$idList)->get();
//        $t=(DB::table('payment_order_items')
//            ->select('*')
//            ->whereIn('order_id',$idList)
//            ->get());
//        $t=$t->whereNotNull('order_id')->all();
        return response()->json(
            [
                'success'=>true,
                'payments'=>PaymentOrderItem::whereIn('order_id',$idList)->get(),
//                'query'=>DB::getQueryLog(),
//                'OIL'=>$idList,
            ]
        );

    }
    public function getPaymentDetails(Request $request)
    {
        $user=$request->all()['user'];
        $data =  Validator::make($request->all(),
            [
                'payment_id' => ['required'],
            ]
        );
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $payment_id=$request->all()['payment_id'];
        $stripe = \Cartalyst\Stripe\Stripe::make(env('STRIPE_SECRET'));
//        $s=$stripe->charges()->retrieve('ch_3JqjAIJ9mJOOefqN1tbxCmbc');
//        $s=$stripe->charges()->find($payment_id);
        return response()->json(
          [
              'success'=>true,
              'payment_details'=>$stripe->charges()->find($payment_id)
          ]
        );
    }

}

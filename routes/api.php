<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//seller address crud start
Route::post('/addSellerAddress', [\App\Http\Controllers\SellerAddressController::class, 'create'])->middleware('JwtAuthUser');
Route::post('/updateSellerAddress', [\App\Http\Controllers\SellerAddressController::class, 'update'])->middleware('JwtAuthUser');
Route::post('/getSellersAddress',
    function (Request $request) {
        $address=\App\Models\User::find($request->all()['user']->id)->seller->sellerAddress;
        return response()->json(
            [
                'success'=> (bool)$address,
                'address'=>$address??'',
            ]
            ,$address?200:404
        );
    }
)->middleware('JwtAuthUser');
Route::get('/getAddressFromLatLong',
    function (Request $request) {
        return \App\Models\SellerAddress::where('lat', $request->all()['lat'])->where('long', $request->all()['long'])->get();
    }
);
//seller address crud end
Route::get('/chekDesc/{id}'//forTesting
    ,
    function ($id) {
        return \App\Models\ServiceRequest::find($id)->requestDescription();
    }
);
//search start
Route::get('/searchSeller', [\App\Http\Controllers\SearchController::class, 'searchSeller']);//search seller with name like
Route::get('/searchCat', [\App\Http\Controllers\SearchController::class, 'searchCat']);//search cat with name like
Route::get('/searchItem', [\App\Http\Controllers\SearchController::class, 'searchItem']);//search item with name like
//search end
//user register, login, logout, role and session
Route::post('/mobileRegister', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/getCurrentUser', [\App\Http\Controllers\UserController::class, 'getCurrentUser']);
Route::post('/mobileLogOut', [\App\Http\Controllers\UserController::class, 'logout']);
Route::post('/getPrivileges', [\App\Http\Controllers\UserController::class, 'getPrivileges']);
Route::post('/getRole', [\App\Http\Controllers\UserController::class, 'getRole'])->middleware('JwtAuthUser');
//profile
Route::post('/updateProfile', [\App\Http\Controllers\ProfileController::class, 'update'])->middleware('JwtAuthUser');
Route::post('/getProfilePicture', [\App\Http\Controllers\ProfileController::class, 'getProfilePicture'])->middleware('JwtAuthUser');
Route::post('/setProfilePicture', [\App\Http\Controllers\ProfileController::class, 'updateImage'])->middleware('JwtAuthUser');

Route::post('/updateUser', [\App\Http\Controllers\UserController::class, 'update'])->middleware('JwtAuthUser');
Route::post('/getAllServicesWithChildren'
    ,
    function () {
        return ['data' => Category::with('children')->whereNull('category_id')->get()];
    }
);
Route::post('/getSellerInfo',
    [\App\Http\Controllers\SellerController::class, 'getSellerInfo']
)->middleware('JwtAuthUser');

Route::post('/sellerShowProfile',[\App\Http\Controllers\SellerController::class,'sellerShowProfile']);

Route::post('/sells', function (Request $request) {
    if($request->all()['user']->role_id!=2)
        return response()->json(
            [
                'success' => false,
                'message'=>'This user is not registered as a service provider',
            ], 400
        );
    $user = \App\Models\User::find($request->all()['user']->id);
    $a = \App\Models\Item::with('category.category')->where('seller_id', $user->seller->id)->get();
    return response()->json(
        [
            'success' => true,
            'profile' => $user->profile,
            'items' => $a,
        ], 200
    );
}
)->middleware('JwtAuthUser');

Route::post('/registerSeller', [\App\Http\Controllers\SellerController::class, 'registerSeller'])->middleware('JwtAuthUser');//register service provider only user not registered can register also create a folder for user in drive
//seller's items create update
Route::post('/createPost', [\App\Http\Controllers\ItemController::class, 'create'])->name('item.create')->middleware('JwtAuthUser');//create a post only registered seller can create a post
Route::post('/updatePost', [\App\Http\Controllers\ItemController::class, 'update'])->name('item.update')->middleware('JwtAuthUser');//create a post only registered seller can create a post


Route::post('/categoryItems', function (Request $request) {
    return \App\Models\Item::with('category')->wherein('category_id', Arr::pluck(DB::table('categories')
        ->select('id')
        ->where('category_id', $request->all()['id'])
        ->get(), 'id'))->get();
}
);
Route::post('/getLastLvlCat', function (Request $request) {
    return[
        'success'=>true,
        'parent'=>Category::find($request->all()['id']),
        'children'=>\App\Models\Category::wherein('category_id', Arr::pluck(DB::table('categories')
        ->select('id')
        ->where('category_id', $request->all()['id'])
        ->get(), 'id'))->get()
        ];
}
);
//request
Route::post('/requestService',
    [\App\Http\Controllers\ServiceRequestController::class, 'create']
)->middleware('JwtAuthUser');//register a new request and return it
Route::post('/getRequest', [\App\Http\Controllers\ServiceRequestController::class, 'getRequest']);//return request against an id
Route::post('/getUserRequests', [\App\Http\Controllers\ServiceRequestController::class, 'userRequests']);
//cart
Route::post('/addToCart', [\App\Http\Controllers\CartController::class, 'addToCart'])->middleware('JwtAuthUser');
Route::post('/getCart', [\App\Http\Controllers\CartController::class, 'getCart'])->middleware('JwtAuthUser');
Route::post('/removeFromCart', [\App\Http\Controllers\CartController::class, 'removeFromCart'])->middleware('JwtAuthUser');
//end cart
//order start
Route::post('/orderCreate', [\App\Http\Controllers\OrderController::class, 'create'])->middleware('JwtAuthUser');
Route::post('/getOrders', [\App\Http\Controllers\OrderController::class, 'getOrders'])->middleware('JwtAuthUser');
Route::post('/changeStatus', [\App\Http\Controllers\OrderController::class, 'changeStatus'])->middleware('JwtAuthUser');

//order end
//invoice
Route::post('/createInvoice', [\App\Http\Controllers\InvoiceController::class, 'createInvoice'])->middleware('JwtAuthUser');
Route::post('/getInvoices',
function (Request $request)
{
    $user=\App\Models\User::find($request->all()['user']->id);
    return \App\Models\Invoice::with('invoiceItems.item')->where('seller_id',$user->seller->id)->get();
}
)->middleware('JwtAuthUser');

//end invoice

//payment start
Route::get('/payCheck',
    function (Request $request)
    {

        $stripe = \Cartalyst\Stripe\Stripe::make(env('STRIPE_SECRET'));
//        return  $stripe->charges()->find('ch_3Jn1vMJ9mJOOefqN0so85JGa');
        $token = $stripe->tokens()->create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 10,
                'cvc'       => 314,
                'exp_year'  => 2022,
            ],
        ]);
//        return response()->json(
//            [
//                'stripe'=>$stripe,
//                'token'=>$token,
//            ]
//        );
        try{
            $stripe = $stripe->charges()->create([
                'amount'=>100,
                'currency'=>'PKR',
                'source' => $token['id'],
                'receipt_email' =>'a@a.com',
                'description' => "Test payment"
            ]);
            return response()->json($stripe['id']);
        }catch (Exception $exception)
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>$exception->getMessage(),
                ]
            );
        }
        return response()->json(
            [
                'stripe'=>$stripe,
                'token'=>$token,
                ]
        );
    }
);
Route::Post('/getStripToken', [\App\Http\Controllers\PaymentOrderItemsController::class, 'getStripToken'])->middleware('JwtAuthUser');
//payment end
//

//api for testing only

Route::get('/readText/', function () {
    $cont = Storage::disk('google')->get('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6/1LuNXjY18A0dTzRG4JKs6updh67aA3i8J');
    dump(Storage::disk('google')->getMetaData('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6/1LuNXjY18A0dTzRG4JKs6updh67aA3i8J'));
    dd($cont);
}
);

Route::get('/lel', function () {
    return
        response()->json(
        [
            'orders'=>\App\Models\Order::with('orderItems')->get(),
        ]
        );
}
);


// for connection test
Route::get('/checkSpeed', [\App\Http\Controllers\ProfileController::class, 'checkSpeed']);
Route::get('/checkApi', function () {
    return ['success' => true, 'message' => 'done'];
});
Route::post('/jwtmiddelwarecheck'
    ,
    function (Request $request) {
        $user = $request->all()['user'];
        return $user->email;
    }
)->middleware('JwtAuthUser');
//test-at-your-door-step old hosting

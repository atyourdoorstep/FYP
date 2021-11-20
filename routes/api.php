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
Route::get('/chekDesc/{id}'//forTesting
    ,
    function ($id) {
        return \App\Models\ServiceRequest::find($id)->requestDescription();
    }
);
//feedBack
Route::post('/createFeedBack', [\App\Http\Controllers\UserFeedBackController::class, 'create'])->middleware('JwtAuthUser');//register a new request and return it
Route::post('/getFeedBack', [\App\Http\Controllers\UserFeedBackController::class, 'getFeedBack']);//return request against an id
Route::post('/getUserFeedBack', [\App\Http\Controllers\UserFeedBackController::class, 'userFeedBack']);
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

Route::Post('/getStripToken', [\App\Http\Controllers\PaymentOrderItemsController::class, 'getStripToken'])->middleware('JwtAuthUser');
//payment end
//discount code start
Route::post('/createDiscountCode', [\App\Http\Controllers\DiscountCodeController::class, 'create'])->middleware('JwtAuthUser');
Route::post('/getDiscount', [\App\Http\Controllers\DiscountCodeController::class, 'getDiscount'])->middleware('JwtAuthUser');
Route::post('/destroyCode', [\App\Http\Controllers\DiscountCodeController::class, 'destroy'])->middleware('JwtAuthUser');

//discount code end
//wallet start
Route::post('/getWallet', [\App\Http\Controllers\WalletController::class, 'getWallet'])->middleware('JwtAuthUser');

//wallet end
//review start
Route::post('/canRateItem', [\App\Http\Controllers\ReviewController::class, 'canRate'])->middleware('JwtAuthUser');
Route::post('/RateItem', [\App\Http\Controllers\ReviewController::class, 'create'])->middleware('JwtAuthUser');
Route::get('/getRating'
    ,
    function (Request $request) {
        return response()->json(
            [
                'success'=>true,
                'rating'=>\App\Models\Item::find($request->all()['item_id'])->rating(),
            ],200
        );
    }
);
//review end
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
Route::get('/rc'
    ,
    function (Request $request) {
      $r=\App\Models\Item::with('rating')->where('name', 'LIKE',  "%".$request->search . "%")->get();
        return $r;
    }
);
//test-at-your-door-step old hosting


    Route::get('/nigger'
    ,
    function (Request $request) {
        $iId=Arr::pluck(DB::table('order_items')
            ->select('item_id')
            ->get(), 'item_id');
        $sellers=Arr::pluck(DB::table('items')
            ->select('seller_id')
            ->whereIn('id',$iId)
            ->get(), 'seller_id');
        $z=\App\Models\Seller::with(['items.orderItems','wallet'])->whereIn('id',$sellers)->get();
        foreach ($z as $seller)
        {
            foreach ($seller->items as $item)
            {
                if($item->orderItems??'')
                {
                    foreach ($item->orderItems as $orderItem)
                    {
                        if($orderItem->status=='completed') {
                            $total = (($item->price * $orderItem->quantity) - $orderItem->discount);
                            $seller->wallet->amount += $total * ((100 - env('APP_CUT')) / 100);
                            dump($seller->wallet);
                            $seller->wallet->save();
                        }
                    }
                }
            }
        }
        return 'done';
    }
);

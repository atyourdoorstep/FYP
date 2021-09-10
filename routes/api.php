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
Route::post('/jwtmiddelwarecheck'
    ,
    function (Request $request) {
        $user = $request->all()['user'];
        return $user->email;
    }
)->middleware('JwtAuthUser');


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


Route::post('/sells/{id}', function ($id) {
//    \DB::enableQueryLog();
    $a = \App\Models\Item::with('category.category')->where('seller_id', $id)->get();
    return [
//        'query'=>\DB::getQueryLog(),
        'success' => true,
        'response' => $a,
    ];
}
);

Route::post('/registerSeller', [\App\Http\Controllers\SellerController::class, 'registerSeller'])->middleware('JwtAuthUser');//register service provider only user not registered can register also create a folder for user in drive
Route::post('/createPost', [\App\Http\Controllers\ItemController::class, 'create'])->name('item.create')->middleware('JwtAuthUser');//create a post only registered seller can create a post


Route::post('/categoryItems', function (Request $request) {
    return \App\Models\Item::with('category')->wherein('category_id', Arr::pluck(DB::table('categories')
        ->select('id')
        ->where('category_id', $request->all()['id'])
        ->get(), 'id'))->get();
}
);


Route::post('/requestService',
    [\App\Http\Controllers\ServiceRequestController::class,'create']
)->middleware('JwtAuthUser');
Route::get('/readText/', function () {
    $cont = Storage::disk('google')->get('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6/1LuNXjY18A0dTzRG4JKs6updh67aA3i8J');
    dump(Storage::disk('google')->getMetaData('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6/1LuNXjY18A0dTzRG4JKs6updh67aA3i8J'));
    dd($cont);
}
);

// for connection test
Route::get('/checkSpeed', [\App\Http\Controllers\ProfileController::class, 'checkSpeed']);
Route::get('/checkApi', function () {
    return ['success' => true, 'message' => 'done'];
});




//test-at-your-door-step old hosting

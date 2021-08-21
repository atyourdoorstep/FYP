<?php

use App\Models\Category;
use Illuminate\Http\Request;
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

Route::post('/mobileRegister', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/getCurrentUser', [\App\Http\Controllers\UserController::class, 'getCurrentUser']);
Route::post('/mobileLogOut', [\App\Http\Controllers\UserController::class, 'logout']);
Route::post('/getPrivileges',[\App\Http\Controllers\UserController::class,'getPrivileges']);
Route::post('/getRole',[\App\Http\Controllers\UserController::class,'getRole']);

Route::get('/updateProfile',[\App\Http\Controllers\ProfileController::class,'update']);

Route::post('/getProfilePicture',[\App\Http\Controllers\ProfileController::class,'getProfilePicture']);

Route::post('/setProfilePicture',[\App\Http\Controllers\ProfileController::class,'updateImage']);

Route::post('/updateUser',[\App\Http\Controllers\UserController::class,'update']);

//Route::post('/getParentServices',function()
//{
//    return \App\Models\Category::all()->whereNull('category_id');
//}
//);//get all the parent services not needed
Route::post('/getAllServicesWithChildren'
    ,
    function ()
    {
        return ['data' => Category::with('children')->whereNull('category_id')->get()];
    }
);
Route::post('/sells/{id}',function ($id)
{
    //withAll
    $a= \App\Models\Seller::find($id)->with('items.category.category')->get();
//    $a= \App\Models\Seller::find($id)->with('category.children.items')->get();
    return $a;
    return $a[0]['category']->with('children')->get();
}
);

Route::post('/registerSeller',[\App\Http\Controllers\SellerController::class,'registerSeller']);//register service provider only user not registered can register also create a folder for user in drive
Route::post('/createPost',[\App\Http\Controllers\ItemController::class,'create'])->name('item.create');//create a post only registered seller can create a post




















// for connection test
Route::get('/checkSpeed',[\App\Http\Controllers\ProfileController::class,'checkSpeed']);
Route::get('/checkApi', function () {
    return ['success'=>true,'message'=>'done'];
});




//test-at-your-door-step old hosting

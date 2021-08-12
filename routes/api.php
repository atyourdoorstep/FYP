<?php

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
Route::post('/updateProfile',[\App\Http\Controllers\ProfileController::class,'update']);

Route::get('/getProfilePicture',[\App\Http\Controllers\ProfileController::class,'getProfilePicture']);

Route::post('/updateUser',[\App\Http\Controllers\UserController::class,'update']);



Route::get('/checkSpeed',[\App\Http\Controllers\ProfileController::class,'checkSpeed']);
//done
Route::get('/checkApi', function () {
    return ['success'=>true,'message'=>'done'];
});

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/mlog', [\App\Http\Controllers\Auth\LoginController::class,

function ()
{
    return \Illuminate\Support\Facades\Auth::user();
}
]);

Route::get('/mobRegTry',
    function ()
    {
        return view('mobReg');
    }

);

Route::get('/checkToken',
function ()
{
    return view('mobiletry');
}
);

//api
/*
Route::post('/mobileRegister', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/mobileLogOut', [\App\Http\Controllers\UserController::class, 'logout']);
Route::get('/getSessionToken',[\App\Http\Controllers\UserController::class,'getSessionToken']);
*/


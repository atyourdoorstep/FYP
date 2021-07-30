<?php

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

Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);

Route::get('/mobRegTry',
    function ()
    {
        return view('mobReg');
    }

);
Route::post('/mReg', [\App\Http\Controllers\UserController::class, 'register']);

Route::get('/checkToken',
function ()
{
    return view('mobiletry');
}
);

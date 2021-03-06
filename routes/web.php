<?php

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
    return redirect(\route('home'));
});
Route::get('/email', function () {
//    return new \App\Mail\AdminResponseMail();
    Mail::to('mussabayubawan1@gmail.com')->send(new \App\Mail\AdminResponseMail());

    if(Mail::failures() != 0) {
        return "<p> Success! Your E-mail has been sent.</p>";
    }

    else {
        return "<p> Failed! Your E-mail has not sent.</p>";
    }
});
Auth::routes();
Route::get('/request', [\App\Http\Controllers\ServiceRequestController::class, 'requestList']);
Route::post('/requestDetails/{id}', [\App\Http\Controllers\ServiceRequestController::class, 'requestDetails']);
Route::get('/feedback', [\App\Http\Controllers\UserFeedBackController::class, 'requestList']);
Route::post('/feedBackDetails/{id}', [\App\Http\Controllers\UserFeedBackController::class, 'requestDetails']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/changeSellerStatus', [\App\Http\Controllers\AdminController::class, 'changeSellerStatus']);

Route::middleware('admin')->get('/addCategory',[\App\Http\Controllers\CategoryController::class,'index'])->name('category.add');

Route::middleware('admin')->get('/categoryTree',[\App\Http\Controllers\CategoryController::class,'categoryTree'])->name('category.tree');

Route::middleware('admin')->post('/regCategory', [\App\Http\Controllers\CategoryController::class,'create'])->name('/regCategory');
Route::middleware('admin')->post('/cat/{id}/edit', '\App\Http\Controllers\CategoryController@edit')->name('category.edit');//edit form
Route::middleware('admin')->patch('/Cat/{id}', [\App\Http\Controllers\CategoryController::class,'update'])->name('/Cat.update');//update from controller
Route::middleware('admin')->get('/catList/{parent?}', [\App\Http\Controllers\AdminController::class,'catIndex'])->name('category.list');
Route::middleware('admin')->get('/sellerList', [\App\Http\Controllers\AdminController::class,'sellerList']);
Route::middleware('admin')->get('/serviceRequests/', [
    function(Request $request)
    {
        return view('mobiletry');
    }
]);










Route::get('/checkUser', function ()
{
    return Auth::user()->role->role_name;
}
);

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
Route::get('/dirCheck/{name}/{parent?}',
    function ($name,$parent='')
    {
        return app('App\Http\Controllers\SellerFolderController')->create($name,69);
        return app('App\Http\Controllers\SellerFolderController')->createDir($name,$parent);

//        $dir=Storage::disk('google')->makeDirectory($name, 0775, true);
        $dir=Storage::disk('google')->directories();
        dump($dir);
        $meta=Storage::disk('google')->getMetaData($dir[0]);
        $path='';
        foreach ($dir as $d)
        {
            $meta=Storage::disk('google')->getMetaData($d);
            if($meta['name']===$name)
            {
                $path=$d;
            }
        }
        return $path;
    }
);

Route::post('test', function( Symfony\Component\HttpFoundation\Request $req) {
    $path='1EQ8AdcDPSCheUqGYUarxy6umeE5u18tT/1NRiqthaqWsnzSxrQ9lf8XHoaOZQTtGI_';
//    $path='';

    $data = \request()->validate(
        [
            'image' => 'required',
        ]
    );
    $imagePath = $data['image']->store($path, 'google');
    $url=Storage::disk('google')->url($imagePath);
    dd($url);
});

//api
/*
Route::post('/mobileRegister', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/mobileLogOut', [\App\Http\Controllers\UserController::class, 'logout']);
Route::get('/getSessionToken',[\App\Http\Controllers\UserController::class,'getSessionToken']);
*/


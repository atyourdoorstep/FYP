<?php

use Illuminate\Support\Facades\Auth;
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

Route::post('test', function( Symfony\Component\HttpFoundation\Request $req) {
//    $content = collect(Storage::disk('google')->listContents('/', false));
//    foreach ($content as $key => $value) {
//        if($value['name'] == 'profilePictures')
//            $root = $value['path'];
//    }
//    dd($root);
//    $dir = '/'.$root;
//    dump($dir);
//    $recursive = true; // Get subdirectories also?
//    $contents = collect(Storage::disk('google')->listContents($dir, $recursive));
    //dd($contents);
    $path='1hKpXA8JfkON1MvuSDw9vWhCYQOUsoief';

    $data = \request()->validate(
        [
            'image' => 'required',
        ]
    );
    $imagePath = $data['image']->store($path, 'google');
    $url=Storage::disk('google')->url($imagePath);
    dd($url);

//    dd($req->file('image')->store('profilePictures','google'));
//     return response()->json(
//        [
//            'suc'=>true,
//        'img'=>request()->all()
//        ]);
//    Storage::disk('google')->put('test.txt', 'Hello World');
   // $x=Storage::disk('google')->get('test.txt');
    //dump($x);
});

//api
/*
Route::post('/mobileRegister', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/mobileLogin', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/mobileLogOut', [\App\Http\Controllers\UserController::class, 'logout']);
Route::get('/getSessionToken',[\App\Http\Controllers\UserController::class,'getSessionToken']);
*/


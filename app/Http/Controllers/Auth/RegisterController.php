<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fName' => ['required','regex:/^[a-zA-Z ]+$/', 'string', 'min:8','max:255','regex:/^[\w-]*$/'],
            'lName' => ['required', 'string', 'min:8', 'max:255','regex:/^[\w-]*$/'],
            'CNIC' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:13', 'unique:users'],
            'contact' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:11', 'unique:users'],
            'address' => [ 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'api_token'=>['string','max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'fName' => $data['fName'],
            'lName' => $data['lName'],
            'address' => $data['address'],
            'date_of_birth' => $data['date_of_birth'],
            'contact' => $data['contact'],
            'CNIC' => $data['CNIC'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            //'api_token' => Str::random(60),
        ]);
    }
}

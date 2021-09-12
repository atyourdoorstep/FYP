<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Auth::user();
//        if (Auth::user()&&Auth::user()->role->role_name == 'admin')
        if (Auth::user()&&count(Auth::user()->appAdmin))
        {
            return $next($request);
        }
        Auth::logout();
        return redirect(route('login'))->withErrors(['error', 'Only Users with admin role are allowed to login']);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtAuthUser
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
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        if(!$user->isSuccessful())
            return $user;
        $valuesToAdd = ['user' =>$user->getData()->user];
        $request->merge(['user' =>$user->getData()->user]);
        //$request->user=$user->getData()->user;
        return $next($request);
    }
}

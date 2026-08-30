<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

    //if the user is login 
        if(Auth::check()){
            //if the user is not admin 
            if(Auth::user()->role ==='admin'){
              //allow  
               return $next($request);
            }else{
                abort(403, 'Unauthorized action.');
            }
        }else{
            abort(403, 'You are not logged in.');
        }

        
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return $next($request);
        }else{
            if(Auth::user()->role == 'admin'){
                return redirect()->route('admin.dashboard')->with('error', 'Anda sudah login sebagai admin');
            }elseif(Auth::user()->role == 'staff'){
                return redirect()->route('staff.promos.index')->with('error', 'Anda sudah login sebagai staff');  
            }else{
                return redirect()->route('home')->with('error', 'Anda sudah login');
            }
        }
    }
}

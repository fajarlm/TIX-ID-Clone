<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //return $next($request) untuk melanjutkan proses tampilan ke halaman
        if(Auth::check()&& Auth::user()->role == 'admin'){
            return $next($request);
        }
        //jika bukan admin dan blom login, maka akan diarahkan ke halaman home dengan
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
}

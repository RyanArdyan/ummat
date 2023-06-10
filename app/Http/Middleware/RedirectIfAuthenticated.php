<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Tangani permintaan yang akan datang
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // jika user sudah login maka 
            if (Auth::guard($guard)->check()) {
                // kembalikkan alihkan ke RutePenyediaLayanan::rumah
                return redirect(RouteServiceProvider::HOME);
            };
        };

        // jika user belum login maka kembalikkan, lanjutkan permintaan
        return $next($request);
    }
}

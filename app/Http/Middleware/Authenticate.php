<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;


class Authenticate
{
    /**
     * Dapatkan jalur yang user harus dialihkan ketika mereka belum autentikasi atau login
     */
    // publik fungsi tangani(Permintaan $permintaan, Penutupan $lanjut) 
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login atau jika user tidak cek autentikasi
        if (!auth()->check()) {
            // kembalikkan alihkan ke route login.index, lalu kirimkan data berupa sesi yang di flash yaitu variabel status berisi string berikut
            return redirect()->route('login.index')->with('status', 'Silahkan Login Dulu Ya.');
        } 
        // lain jika user nya sudah login atau lain jika autentikasi check
        else if (auth()->check()) {
            // kembalikkan lanjutkan permintaan
            return $next($request);
        };
    }
}


<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Dapatkan jalur yang user harus dialihkan ketika mereka belum autentikasi atau login
     */
    // lindungi fungsi alihkanKe(Permintaan $permintaan) 
    protected function redirectTo(Request $request): ?string
    {
        // kembalikkan alihkan mengharapkan json, jika true maka kosong jika false maka alihkan ke route yang bernama login.index
        return $request->expectsJson() ? null : route('login.index');
    }
}

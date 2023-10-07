<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileSudahLengkap
{
    /**
     * Tangani sebuah permintaan yg akan datang
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // MIDDLEWARE INI HARUS DI DAFTARKAN DI APP/HTTP/KERNEL
    // publik fungsi tangani(Permintaan $permintaan, Penutup $lanjutkan)
    public function handle(Request $request, Closure $next): Response
    {
        // berisi value detail_user_yg_login, column no_wa
        // ambil value detail_user yg autentikasi, column nomor_wa
        $no_wa_user = $request->user()->nomor_wa;

        // jika value variable $no_wa_user sama dengan kosong maka
        if ($no_wa_user === null) {
            // kembalikkan alihkan ke route yg bernama edit_profile, dengan mengirimkan data di sesi
            return redirect()->route('edit_profile')->with('status', 'Anda Harus Melengkapi profile anda terlebih dahulu.');
        }
        // lain jika value variable $no_wa_user tidak sama dengan null
        else if ($no_wa_user !== null) {
            // kembalikkan lanjutkan permintaan
            return $next($request);
        };
    }
}   

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendDonasiController extends Controller
{
    // menampilkan halaman formulir donasi
    public function create(Request $request)
    {
        // ambil value detail_user yg login, column nomor_wa
        $nomor_wa_user = $request->user()->nomor_wa;

        // kembalikkan ke tampilan frontend.donasi.formulir_create, lalu kirimkan data berupa array
        return view('frontend.donasi.formulir_create', [
            // kunci nomor_wa_user berisi value variable $nomor_wa_user
            'nomor_wa_user' => $nomor_wa_user
        ]);
    }
}

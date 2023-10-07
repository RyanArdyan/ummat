<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penceramah;

class FrontendPenceramahController extends Controller
{
    // Method index menampilkan halaman penceramah
    // publik fungsi index
    public function index()
    {
        // ambil semua penceramah, ambil data terbaru
        // berisi penceramah, pilih column user_id agar relasi nya terpanggil nama_penceramah, slug_penceramah, foto_penceramah, dipublikasi_pada di pesan oleh value column updated_at, data yang paling baru, dapatkan semua data nya
        $semua_penceramah = Penceramah::select("nama_penceramah", 'foto_penceramah')->orderBy('updated_at', 'desc')->get();

        // kembalikkan ke tampilan frontend.penceramah.index, kirimkan data berupa array, 
        return view('frontend.penceramah.index', [
            // key semua_penceramah berisi value $semua_penceramah
            'semua_penceramah' => $semua_penceramah
        ]);
    }
}

// 
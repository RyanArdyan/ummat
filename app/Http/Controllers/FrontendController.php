<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// perluas kelas dasar 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class FrontendController extends Controller
{
    public function index()
    {
        // berisi mengambil data jadwal adzan dari api external, aku dapat dari https://github.com/renomureza/waktu-sholat
        // berisi http dapatkan data dari url berikut, lalu ubah data nya menjadi json
        $data_adzan_dari_api_external = Http::get('https://waktu-sholat.vercel.app/prayer?latitude=0.04137222222222222&longitude=109.3363611111111')->json();

        // berisi semua jadwal adzam
        $semua_jadwal_adzan = $data_adzan_dari_api_external["prayers"];
        // $semua_jadwal_adzan = $data_adzan_dari_api_external["prayers"][0]["date"];

        // tanggal, tahun (year), bulan (month) tanpa mencetak 0 jadi misalnya bulan 08 maka akan menjadi 8 dan hari (day)
        $tanggal_hari_ini = date('Y-n-d');

        // berisi array karena aku nanti aku akan push data ke dalamnya
        $wadah = [];

        // lopping value dari variable $semua_jadwal_adzan
        // untukSetiap ($semua_jadwal_adzan sebagai $jadwal_adzan)
        foreach ($semua_jadwal_adzan as $jadwal_adzan) {
            // jika tanggal hari tidak sama dengan value variable $jadwal_adzan["date"]
            if ($tanggal_hari_ini === $jadwal_adzan["date"]) {
                $wadah[] = $jadwal_adzan['time'];
                break;
            };
        };

        // dd($wadah);

        // kembalikkan ke tampilan frontend/app, kirimkan data berupa array
        return view('frontend.home.index', ["jadwal_adzan" => $wadah]);
    }
}

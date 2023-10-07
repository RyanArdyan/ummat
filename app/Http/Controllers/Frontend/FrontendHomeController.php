<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
// perluas kelas dasar 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontendHomeController extends Controller
{
    // Untuk menampilkan home milik frontend
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
            // jika tanggal hari sama dengan value variable $jadwal_adzan["date"]
            if ($tanggal_hari_ini !== $jadwal_adzan["date"]) {
                // panggil array wadah lalu dorong setiap value variable $jadwal_adzan["time"]
                $wadah[] = $jadwal_adzan['time'];
                // hentikan pengulangan
                break;
            };
        };



        // tanggal awal pada bulan saat ini, sudah pasti dimulai dari tanggal 1
        // y = year, m = month, d = day
        $tanggal_awal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        // misalnya hari tanggal 20 maka berisi 20 lah
        $tanggal_hari_ini = date("Y-m-d");


        // kembalikkan ke tampilan frontend/home/app, kirimkan data berupa array
        return view('frontend.home.index', [
            // key jadwal_adzan berisi value variable $wadah
            "jadwal_adzan" => $wadah,
            // key tanggal_awal berisi value variable $tanggal_awal
            'tanggal_awal' => $tanggal_awal,
            // key tanggal_hari_ini berisi value variable $tanggal_hari_ini
            'tanggal_hari_ini' => $tanggal_hari_ini
        ]);
    }
}

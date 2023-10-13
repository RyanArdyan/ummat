<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
// perluas kelas dasar 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\DonasiManual;
// package laravel datatables untuk menghubungkan package laravel datatables dengan package datatables
use DataTables;

class FrontendHomeController extends Controller
{
    // Untuk menampilkan home milik frontend dan fitur jadwal adzan
    public function index()
    {
        // berisi mengambil data jadwal adzan dari api external, aku dapat dari https://github.com/renomureza/waktu-sholat
        // berisi http dapatkan data dari url berikut, lalu ubah data nya menjadi json
        $data_adzan_dari_api_external = Http::get('https://waktu-sholat.vercel.app/prayer?latitude=0.04137222222222222&longitude=109.3363611111111')->json();

        // berisi semua jadwal adzam
        // berisi panggil value array variable berikut, index prayers
        $semua_jadwal_adzan = $data_adzan_dari_api_external["prayers"];

        // tanggal, tahun (year), bulan (month) tanpa mencetak 0 jadi misalnya bulan 08 maka akan menjadi 8 dan hari (day)
        $tanggal_hari_ini = date('Y-n-d');

        // berisi array karena aku nanti aku akan push data ke dalamnya
        $wadah = [];

        // lopping value dari variable $semua_jadwal_adzan
        // untukSetiap ($semua_jadwal_adzan sebagai $jadwal_adzan)
        foreach ($semua_jadwal_adzan as $jadwal_adzan) {
            // jika value variable $tanggal_hari_ini tidak sama dengan value array $jadwal_adzan, index date
            if ($tanggal_hari_ini !== $jadwal_adzan["date"]) {
                // panggil array wadah lalu dorong setiap value variable $jadwal_adzan["time"]
                $wadah[] = $jadwal_adzan['time'];
                // hentikan pengulangan jika sudah selesai
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
            'tanggal_awal' => $tanggal_awal,
            'tanggal_hari_ini' => $tanggal_hari_ini
        ]);
    }

    // ada 2 parameter yaitu tanggal_awal pastinya tanggal 1 dan tanggal_akhir itu maksudnya tanggal hari ini
    public function data($tanggal_awal, $tanggal_akhir)
    {
        // ambil semua DonasiManual 
        // berisi ambil value table DonasiManual, pilih column donasi_manual_id, dll, dimanaAntara value column dibuat_pada, dari tanggal_awal sampai tanggal_akhir dimana value column status berisi 'Benar', dipesan oleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_donasi_manual = DonasiManual::select('donasi_manual_id', 'user_id', 'jumlah_donasi', 'pesan_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Benar')->orderBy("updated_at", "desc")->get();

        // untuk membuat looping nomor
        $nomor = 1;
        // buat wadah sebagai wadah dari data
        $wadah = [];

        // lakukan pengulangan dari variable $semua_donasi_manual
        // untukSetiap ($semua_donasi_manual sebagai $donasi_manual) 
        foreach ($semua_donasi_manual as $donasi_manual) {
            // buat row sebagai wadah
            $row = [];
            // mulai push data ke array row, index DT_RowIndex
            // panggil array $row, key DT_RowIndex diisi value variable $nomor++
            $row["DT_RowIndex"] = $nomor++;
            // array row, key pendonasi diisi value detail_donasi yang berelasi dengan table user, column name
            $row["pendonasi"] = $donasi_manual->user->name;
            // key jumlah_donasi diisi panggil helper rupiah_bentuk, value detail_donasi, column jumlah_donasi
            $row["jumlah_donasi"] = rupiah_bentuk($donasi_manual->jumlah_donasi);
            $row["pesan_donasi"] = $donasi_manual->pesan_donasi;
            // panggil array $wadah lalu diisi dengan value array $row
            $wadah[] = $row;
        };

        // jumlahkan value column jumlah_donasi yg terpilih
        // DonasiManual pilih value column jumlah_donasi, dimana value column dibuat_pada berisi value variable $tanggal_awal sampai variable $tanggal_akhir dimana value column status sama dengan 'Benar', jumlahkan value column jumlah_donasi yg terpilih
        $total_donasi = DonasiManual::select('jumlah_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Benar')->sum("jumlah_donasi");

        // Isi array ke dalam array $wadah
        // panggil array $wadah lalu pada anak terakhir dibuat sebuah array
        $wadah[] = [
            // key DT_RowIndex diisi string berikut
            "DT_RowIndex" => "",
            'pendonasi' => "Total Donasi",
            // panggil helper rupiah_bentuk lalu kirimkan alue variable total_donasi
            'jumlah_donasi' => rupiah_bentuk($total_donasi),
            'pesan_donasi' => ""
        ];

        // kembalikkan value variable $wadah
        return $wadah;
    }

    // parameter $request berisi value dari key data milik script
    public function ubah_periode(Request $request)
    {
        // berisi tanggal awal
        // berisi $permintaan->tanggal_awal digabung 00:00:00
        $tanggal_awal = $request->tanggal_awal . ' 00:00:00';

        // berisi tanggal akhir
        // berisi $permintaan->tanggal_akhir digabung 00:00:00
        $tanggal_akhir = $request->tanggal_akhir . ' 00:00:00';

        // berisi panggil method data yg berada diluar lalu kirimkan 2 argument untuk mengambil data, anggaplah berisi tr
        $data = $this->data($tanggal_awal, $tanggal_akhir);

        // kembalikkan databtable dari value variable $data
        return DataTables::of($data)
            // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
            // mentahKolom2x pendonasi, dan lain-lain
            ->rawColumns(['pendonasi', 'jumlah_donasi'])
            // buat benar
            ->make(true);
    }

}

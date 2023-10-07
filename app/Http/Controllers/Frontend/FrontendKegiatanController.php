<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// gunakan atau import
use App\Models\KegiatanRutin;
use App\Models\KegiatanSekali;
use Illuminate\Support\Facades\Storage;

class FrontendKegiatanController extends Controller
{
    // method index
    public function index()
    {
        // Komentarkan kode ini jika sudah di hosting karena sudah menggunakan fitur "penjadwalalan tugas"
        // tanggal hari ini misalnya: "2023-08-29
        // berisi tanggal("tahun-bulan-tanggal")
        $tanggal_hari_ini = date("Y-m-d");
        // ambil beberapa baris kegiatan sekali yang tanggal nya dibawah tanggal hari ini misalnya ada kegiatan yg tanggal nya 2023-06-20
        // berisi KegiatanSekali, dimana value column tanggal, value nya di bawah hari ini, dapatkan beberapa data nya
        $beberapa_kegiatan_sekali = KegiatanSekali::where('tanggal', '<', $tanggal_hari_ini)->get();
        // lakukan pengulangan pake foreach untuk mengambil setiap kegiatan_sekali
        // untuksetiap, $beberapa_kegiatan_sekali sebagai $kegiatan_sekali
        foreach ($beberapa_kegiatan_sekali as $kegiatan_sekali) {
            // hapus gambar
            // Penyimpanan::hapus('/public/gambar_kegiatan_sekali/' digabung value detail_kegiatan, column gambar_kegiatan
            Storage::delete('public/gambar_kegiatan_sekali/' . $kegiatan_sekali->gambar_kegiatan);
            // hapus setiap kegiatan_sekali
            // panggil setiap $kegiatan_sekali lalu dihapus
            $kegiatan_sekali->delete();
        };
        // akhir Komentarkan kode ini jika sudah di hosting karena sudah menggunakan fitur "penjadwalalan tugas"
        

        // ambil semua value dari column kegiatan_rutin_id dan lain-lain, data terbaru akan tampil di urutan pertama
        // beriisi KegiatanRutin::pilih('kegiatan_rutin_id', 'nama_kegiatan', 'dan-lain-lain') dimana value column tipe_kegiatan sama dengan value 'Kegiatan Rutin', dapatkan()
        $semua_kegiatan_rutin = KegiatanRutin::select('nama_kegiatan', 'gambar_kegiatan', 'hari', 'jam_mulai')
        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\"at', 'Sabtu', 'Minggu')")
        // jadi misalnya ada 2 kegiaan dai hari senin lalu ada jam 18.00 dan jam 19.00 maka jam 18.00 akan tampil diatas jam 19.00
        // dipesanOleh column jam_mulai, ascending atau naik
        ->orderBy('jam_mulai', 'ASC')
        ->get();
        $semua_kegiatan_sekali = KegiatanSekali::orderBy('updated_at', 'desc')->get();

        // kembalikkan ke tampilan frontend/kegiatan/index lalu kirimkan data berupa array
        return view('frontend.kegiatan.index', [
            // key semua_kegiatan_rutin berisi value variable $semua_kegiatan_rutin
            'semua_kegiatan_rutin' => $semua_kegiatan_rutin,
            'semua_kegiatan_sekali' => $semua_kegiatan_sekali
        ]);
    }
}

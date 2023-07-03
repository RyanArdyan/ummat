<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KegiatanSekali;
use Illuminate\Support\Facades\Storage;

// jadi aku membuat HapusKegiatanSekali sebagai artisan command atau perintah tukang
class HapusKegiatanSekali extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol
     *
     * @var string
     */
    // lindungi #tandaTangan = 
    protected $signature = 'app:hapus-kegiatan-sekali';

    /**
     * Deskripsi perintah konsol
     *
     * @var string
     */
    // lindungi #deskripsi
    protected $description = 'Menghapus beberapa baris kegiatan_sekali yang tanggal nya lebih kecil atau sudah lewat dari tanggal hari ini.';

    // method yg otomatis dijalankan
    // publik fungsi __konstruk
    public function __construct() {
        // panggil method construct milik parent 
        parent::__construct();
    }

    /**
     * Eksekusi perintah konsol
     */
    // publik fungsi menangani
    public function handle()
    {
        // tanggal hari ini misalnya: "2023-07-02
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
        \Log::info('Berhasil menghapus beberapa baris kegiatan_sekali yang tanggal nya lebih kecil atau sudah lewat dari tanggal hari ini.');
    }
}

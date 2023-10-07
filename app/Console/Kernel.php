<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\KegiatanSekali;
use Illuminate\Support\Facades\Storage;
use DateTimeZone;

class Kernel extends ConsoleKernel
{
    // Dua baris kode berikut aku tambahkan 
    // lindungi $perintah beris array
    protected $command = [
        // panggil Commands\HapusKegiatanSekali
        Commands\HapusKegiatanSekali::class
    ];

    /**
     * Definisikan jadwal perintah aplikasi
     */
    // lindungi fungsi jadwal, jadwal $jadwal
    protected function schedule(Schedule $schedule): void
    {
        // $jadwal, perintah, panggil property protected $signature milik HapusKegiatanSekali, setiap hari pada tengah malam
        // $schedule->command('app:hapus-kegiatan-sekali')->daily();



        // Jalankah php artisan schedule:run agar perintah ini dijalankan
        $schedule->call(function() {
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
        })
        // jadi tugas terjadwal diatas akan dijalankan setiap hari pada tengah malam
        ->everyMinute();
    }   


    /**
     * Registrasikan perintah untuk aplikasi
     */
    // lindungi fungsi perintah2x
    protected function commands(): void
    {
        // class kernel muat semua file yang berada di Console/commands
        $this->load(__DIR__.'/Commands');
        // membutuhkan jalur_dasar, routes/console
        require base_path('routes/console.php');
    }

    // Dapatkan zona waktu yang harus digunakan secara default untuk acara terjadwal.
    public function scheduleTimezone(): DateTimeZone|string|null
    {
        // kembalikkan gunakan zona waktu asia/jakarta
        return 'Asia/Jakarta';
    }
}

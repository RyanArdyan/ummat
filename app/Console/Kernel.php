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
        $schedule->command('app:hapus-kegiatan-sekali')->daily();
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

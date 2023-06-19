<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Model untuk pemetaan kebijakan untuk aplikasi.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Registrasi autentikasi apapun atau autorisasi layanan
     */
    // publik fungsi boot(): ruang kosong
    public function boot(): void
    {
        // $user berisi detail user yang login
        // Gerbang, denifisikan is_admin, jalankan fungsi(Pengguna $pengguna)
        Gate::define('is_admin', function(User $user) {
            // jadi jika detail user yang login, value column is_admin nya adalah "1" maka dia adalah admin lalu lanjutkan permintaan
            return $user->is_admin === "1";
        });
    }
}

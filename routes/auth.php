<?php

// Gunakan atau pangil
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AutentikasiController;
use Illuminate\Support\Facades\Route;

// middleware guest didaftarkan di App\Http\Kernel.php, middleware guest adalah alias dari file 
// route tipe perangkat tengah, untuk user yang belum login
// route tipe perangkat tengah, tamu, grup, jalankan fungsi
Route::middleware('guest')->group(function () {
    // route tipe dapatkan, jika user diarahkan ke url registrasi maka arahkan ke RegisteredUserController, method buat, name nya adalah registrasi.buat
    Route::get('registrasi', [RegisteredUserController::class, 'create'])
                ->name('registrasi.create');
    // route tipe kirim, jika user diarahkan ke url registrasi maka arahkan ke RegisteredUserController, method simpan, name nya adalah registrasi.simpan
    Route::post('registrasi', [RegisteredUserController::class, 'store'])->name('registrasi.store');

    // login
    // route tipe dapatkan, ke url /login, ke AutentikasiController, ke method index, name nya adalah login.index
    Route::get('/login', [AutentikasiController::class, 'index'])
                ->name('login.index');
    // route tipe kirim, ke url /login, ke AutentikasiController, ke method simpan, nama nya adalah gabung.simpan
    Route::post('/login', [AutentikasiController::class, 'store'])->name('login.store');


    // route tipe dapatkan, jika user diarahkan ke url lupa-password maka arahkan ke PasswordAturUlangPengendali, method buat, name nya adalah password.permintaan
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    // route tipe kirim, jika user diarahkan ke url lupa-password maka arahkan ke PasswordAturUulangPengendali, method simpan, name nya adalah password.email
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');
    
    // route tipe dapatkan, jika user diarahkan ke url atur_ulang-password lalu kirimkan token maka arahkan ke PasswordBaruPengendali, method buat, name nya adalah password.atur_ulang
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    // route tipe kirim, jika user diarahkan ke url atur_ulang-password maka arahkan ke PasswordBaruPengendali, method simpan, name nya adalah password.simpan
    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

// route tipe perangkat tengah untuk user yang sudah login jadi user harus login dulu untuk mengakses url-url berikut
// route tipe perangkat tengah, autentikasi, grup, jalankan fungsi
Route::middleware('auth')->group(function () {
    // route tipe dapatkan, jika user diarahkan ke url verifikasi-email maka arahkan ke EmailVerifikasiPromptPengendali, name nya adalah verifikasi.pemberitahuan
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');
    // route tipe dapatkan, jika user diarahkan ke url verifikasi-email lalu kirimkan id lalu kirimkan hash maka arahkan ke EmailVerifikasiPromptPengendali, perangkat tengah tertanda, throttle, name nya adalah verifikasi.memeriksa
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    // route tipe kirim, jika user diarahkan ke url email/verifikasi-notifikasi maka arahkan ke EmailVerifikasiNotifikasiPengendali, method simpan, perangkat tengah throttle:6,1, name nya adalah verifikasi.kirim
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    // route tipe dapatkan, jika user diarahkan ke url konfirmasi-password maka arahkan ke KonfirmasiPassowrdPengendali, method tunjukkan, name nya adalah password.konfirmasi
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    // route tipe kirim, jika user diarahkan ke url konfirmasi-password maka arahkan ke KonfirmasiPassowrdPengendali, method simpan
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // route tipe letakkan, jika user diarahkan ke url password maka arahkan ke PasswordPengendali, method perbarui, name nya adalah password.perbarui
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // route tipe kirim, jika user diarahkan ke url logout maka arahkan ke AutentikasiPengendali, method keluar, name nya adalah keluar
    Route::post('logout', [AutentikasiController::class, 'logout'])
                ->name('logout');
});

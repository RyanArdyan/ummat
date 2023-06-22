<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\KegiatanRutinController;

// panggil routes/auth.php
// membutuhkan directori yang sma digabung auth.php
require __DIR__.'/auth.php';

// url
// route tipe dapatkan, jika user di url awal maka jalankan fungsi
Route::get('/', function () {
    // kembali alihkan welcome
    return view('welcome');
});

// middleware untuk admin yang sudah login dan sudah verifikasi di column email_verified_at
// auth di dapatkan dari Kernel.php
// is_admin di dapatkan dari App/Providers/AuthServiceProvider.php, is_admin adalah fitur gate atau gerbang yang didapatkan dari security -> authorization
// hanya admin yang sudah login yang bisa mengakses url berikut
Route::middleware(['can:is_admin', 'auth', 'verified'])->group(function() {
    // dashboard
    // route tipe dapatkan, jika user diarahkan ke url /dashboard maka arahkan ke DashboardController, method index, name nya adalah dashboard.index
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // edit profile
    // route tipe dapatkan, jika user di url /edit-profile maka arahkan ke EditProfileController, method edit, name nya adalah edit_profile
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit_profile');
    // route tipe kirim, jika user di url /edit-profile maka arahkan ke EditProfileController, method perbarui, name nya adalah perbarui_profile
    Route::post('/edit-profile', [EditProfileController::class, 'update'])->name('update_profile');
    // Route tipe post, jika user diarakan ke url berikut, maka arahkan ke EditProfileController dan method perbarui_password, name nya adalah edit_profile.perbarui_password
    Route::post('/edit-profile/update-password', [EditProfileController::class, 'update_password'])->name('edit_profile.update_password');

    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin maka arahkan ke KegiatanRutinController, method index, name nya adalah kegiatan.index
    Route::get('/kegiatan-rutin', [KegiatanRutinController::class, 'index'])->name('kegiatan_rutin.index');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/create maka arahkan ke KegiatanRutinController, method create, name nya adalah kegiatan_rutin.create
    Route::get('/kegiatan-rutin/create', [KegiatanRutinController::class, 'create'])->name('kegiatan_rutin.create');
    // route tipe kirim, jika user dirahkan ke url /kegiatan-rutin/simpan maka arahkan ke KegiatanRutinController, method store, name nya adalah kegiatan_rutin.store
    Route::post('/kegiatan-rutin/store', [KegiatanRutinController::class, 'store'])->name('kegiatan_rutin.store');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/baca maka arahkan ke KegiatanRutinController, method baca, nama nya adalah kegiatan_rutin.baca
    Route::get('/kegiatan-rutin/read', [KegiatanRutinController::class, 'read'])->name('kegiatan_rutin.read');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/edit maka kirimknan vaue kegiatan_id nya agar aku bisa mengambil detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method edit, nama nya adalah kegiatan_rutin.edit
    Route::get('/kegiatan-rutin/edit/{kegiatan_id}', [KegiatanRutinController::class, 'edit'])->name('kegiatan_rutin.edit');
    // route tipe letakkan, jika user dirahkan ke url /kegiatan-rutin/ maka kirimknan value kegiatan_id nya agar aku bisa memperbarui detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method update, nama nya adalah kegiatan_rutin.update
    Route::put('/kegiatan-rutin/{kegiatan_id}', [KegiatanRutinController::class, 'update'])->name('kegiatan_rutin.update');
});


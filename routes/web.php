<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\KegiatanRutinController;
use App\Http\Controllers\KegiatanSekaliController;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\HomeController;

// panggil routes/auth.php
// membutuhkan directori yang sma digabung auth.php
require __DIR__.'/auth.php';

// route tipe alihkan, jika user di url awal maka arahkan ke url /login
Route::redirect('/', '/login');


// middleware untuk admin yang sudah login dan sudah verifikasi di column email_verified_at
// auth di dapatkan dari App/Http/Kernel.php
// is_admin di dapatkan dari App/Providers/AuthServiceProvider.php, is_admin adalah fitur gate atau gerbang yang didapatkan dari security -> authorization
// hanya admin yang sudah login yang bisa mengakses url berikut
Route::middleware(['can:is_admin', 'auth', 'verified'])->group(function() {
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/create maka arahkan ke KegiatanRutinController, method create, name nya adalah kegiatan_rutin.create
    Route::get('/kegiatan-rutin/create', [KegiatanRutinController::class, 'create'])->name('kegiatan_rutin.create');
    // route tipe kirim, jika user dirahkan ke url /kegiatan-rutin/simpan maka arahkan ke KegiatanRutinController, method store, name nya adalah kegiatan_rutin.store
    Route::post('/kegiatan-rutin/store', [KegiatanRutinController::class, 'store'])->name('kegiatan_rutin.store');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/baca maka arahkan ke KegiatanRutinController, method baca, nama nya adalah kegiatan_rutin.baca
    Route::get('/kegiatan-rutin/read', [KegiatanRutinController::class, 'read'])->name('kegiatan_rutin.read');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/edit maka kirimknan vaue kegiatan_rutin_id nya agar aku bisa mengambil detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method edit, nama nya adalah kegiatan_rutin.edit
    Route::get('/kegiatan-rutin/edit/{kegiatan_rutin_id}', [KegiatanRutinController::class, 'edit'])->name('kegiatan_rutin.edit');
    // route tipe letakkan, jika user dirahkan ke url /kegiatan-rutin/ maka kirimknan value kegiatan_id nya agar aku bisa memperbarui detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method update, nama nya adalah kegiatan_rutin.update
    Route::put('/kegiatan-rutin/{kegiatan_id}', [KegiatanRutinController::class, 'update'])->name('kegiatan_rutin.update');
    // route tipe kirim, jika user dirahkan ke url /kegiatan-rutin/hancurkan maka arahkan ke KegiatanRutinController, method hancurkan, name nya adalah kegiatan_rutin.hancurkan
    Route::post('/kegiatan-rutin/destroy', [KegiatanRutinController::class, 'destroy'])->name('kegiatan_rutin.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-sekali/create maka arahkan ke KegiatanSekaliController, method create, name nya adalah kegiatan_sekali.create
    Route::get('/kegiatan-sekali/create', [KegiatanSekaliController::class, 'create'])->name('kegiatan_sekali.create');
    // route tipe kirim, jika user dirahkan ke url /kegiatan-sekali/simpan maka arahkan ke KegiatanSekaliController, method simpan, name nya adalah kegiatan_sekali.simpan
    Route::post('/kegiatan-sekali/store', [KegiatanSekaliController::class, 'store'])->name('kegiatan_sekali.store');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-sekali/baca maka arahkan ke KegiatanSekaliController, method baca, nama nya adalah kegiatan_sekali.baca
    Route::get('/kegiatan-sekali/read', [KegiatanSekaliController::class, 'read'])->name('kegiatan_sekali.read');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-sekali/edit maka kirimknan value kegiatan_sekali_id nya agar aku bisa mengambil detail kegiatan_sekali berdasarkan kegiatan_sekali_id arahkan ke KegiatanSekaliController, method edit, nama nya adalah kegiatan_sekali.edit
    Route::get('/kegiatan-sekali/edit/{kegiatan_sekali_id}', [KegiatanSekaliController::class, 'edit'])->name('kegiatan_sekali.edit');
    // route tipe letakkan, jika user dirahkan ke url /kegiatan-sekali/ maka kirimknan value kegiatan_sekali_id nya agar aku bisa memperbarui detail kegiatan_sekali berdasarkan kegiatan_sekali_id arahkan ke KegiatanSekaliController, method update, nama nya adalah kegiatan_sekali.update
    Route::put('/kegiatan-sekali/{kegiatan_sekali_id}', [KegiatanSekaliController::class, 'update'])->name('kegiatan_sekali.update');
    // route tipe kirim, jika user dirahkan ke url /kegiatan-sekali/hancurkan maka arahkan ke KegiatanSekaliController, method hancurkan, name nya adalah kegiatan_sekali.hancurkan
    Route::post('/kegiatan-sekali/destroy', [KegiatanSekaliController::class, 'destroy'])->name('kegiatan_sekali.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /doa/create maka arahkan ke DoaController, method create, name nya adalah doa.create
    Route::get('/doa/create', [DoaController::class, 'create'])->name('doa.create');
    // route tipe kirim, jika user dirahkan ke url /doa maka arahkan ke DoaController, method simpan, name nya adalah doa.simpan
    Route::post('/doa', [DoaController::class, 'store'])->name('doa.store');
    // route tipe dapatkan, jika user dirahkan ke url /doa/baca maka arahkan ke DoaController, method baca, nama nya adalah doa.baca
    Route::get('/doa/read', [DoaController::class, 'read'])->name('doa.read');
    // route tipe dapatkan, jika user dirahkan ke url /doa/edit maka kirimkan value doa_id nya agar aku bisa mengambil detail doa berdasarkan doa_id arahkan ke DoaController, method edit, nama nya adalah doa.edit
    Route::get('/doa/edit/{doa}', [DoaController::class, 'edit'])->name('doa.edit');
    // route tipe letakkan, jika user dirahkan ke url /doa/ maka kirimknan value doa_id nya agar aku bisa memperbarui detail doa berdasarkan doa_id arahkan ke DoaController, method update, nama nya adalah doa.update
    Route::put('/doa/{doa}', [DoaController::class, 'update'])->name('doa.update');
    // route tipe kirim, jika user dirahkan ke url /doa/hancurkan maka arahkan ke DoaController, method hancurkan, name nya adalah doa.hancurkan
    Route::post('/doa/destroy', [DoaController::class, 'destroy'])->name('doa.destroy');
});


// auth di dapatkan dari Kernel.php
// hanya admin yang sudah login dan verifikasi di column email_verified_at yang bisa mengakses url berikut
// route group middleware, untuk yang sudah lgin dan verifikasi di colum email_diverifikasi_pada yang bisa mengakses route berikut
Route::middleware(['auth', 'verified'])->group(function() {
    // home
    // route tipe tampilan, jika user diarahkan ke url /home maka arahkan ke tampilan home.index, name nya adalah home.index
    Route::view('/home', 'home.index')->name('home.index');

    // edit profile
    // route tipe dapatkan, jika user di url /edit-profile maka arahkan ke EditProfileController, method edit, name nya adalah edit_profile
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit_profile');
    // route tipe kirim, jika user di url /edit-profile maka arahkan ke EditProfileController, method perbarui, name nya adalah perbarui_profile
    Route::post('/edit-profile', [EditProfileController::class, 'update'])->name('update_profile');
    // Route tipe post, jika user diarakan ke url berikut, maka arahkan ke EditProfileController dan method perbarui_password, name nya adalah edit_profile.perbarui_password
    Route::post('/edit-profile/update-password', [EditProfileController::class, 'update_password'])->name('edit_profile.update_password');

    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin maka arahkan ke KegiatanRutinController, method index, name nya adalah kegiatan_rutin.index
    Route::get('/kegiatan-rutin', [KegiatanRutinController::class, 'index'])->name('kegiatan_rutin.index');


    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-sekali maka arahkan ke KegiatanSekaliController, method index, name nya adalah kegiatan_sekali.index
    Route::get('/kegiatan-sekali', [KegiatanSekaliController::class, 'index'])->name('kegiatan_sekali.index');

    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-sekali maka arahkan ke KegiatanSekaliController, method index, name nya adalah kegiatan_sekali.index
    Route::get('/kegiatan-sekali', [KegiatanSekaliController::class, 'index'])->name('kegiatan_sekali.index');
    
    // route tipe dapatkan, jika user dirahkan ke url /doa maka arahkan ke DoaController, method index, name nya adalah doa.index
    Route::get('/doa', [DoaController::class, 'index'])->name('doa.index');
    // route tipe dapatkan, jika user dirahkan ke url /doa lalu tangkap dan kirimkan doa_id ke parameter method show milik DoaController, name nya adalah doa.show
    Route::get('/doa/{doa}', [DoaController::class, 'show'])->name('doa.show');
});



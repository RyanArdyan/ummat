<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\KegiatanRutinController;
use App\Http\Controllers\KegiatanSekaliController;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PostinganController;
use App\Http\Controllers\PenceramahController;


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

    // route tipe dapatkan, jika user dirahkan ke url /kategori/create maka arahkan ke KategoriController, method create, name nya adalah kategori.create
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    // route tipe kirim, jika user dirahkan ke url /kategori maka arahkan ke KategoriController, method simpan, name nya adalah kategori.simpan
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    // route tipe dapatkan, jika user dirahkan ke url /kategori/baca maka arahkan ke KategoriController, method baca, nama nya adalah kategori.baca
    Route::get('/kategori/read', [KategoriController::class, 'read'])->name('kategori.read');
    // route tipe dapatkan, jika user dirahkan ke url /kategori/edit maka kirimkan value slug_kategori pake {kategori:slug_kategori} karena aku tidak mengirim primary key, biarkan {kategori:slug_kategori} karena aku menggunakan fitur pengikatan route model, agar aku bisa mengambil detail kategori berdasarkan slug_kategori, arahkan ke KategoriController, method edit, nama nya adalah kategori.edit
    Route::get('/kategori/edit/{kategori:slug_kategori}', [KategoriController::class, 'edit'])->name('kategori.edit');
    // route tipe letakkan, jika user dirahkan ke url /kategori/ maka kirimkan value kategori_id nya agar aku bisa memperbarui detail kategori berdasarkan kategori_id arahkan ke KategoriController, method update, nama nya adalah kategori.update
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    // route tipe kirim, jika user dirahkan ke url /kategori/hancurkan maka arahkan ke KategoriController, method hancurkan, name nya adalah kategori.hancurkan
    Route::post('/kategori/destroy', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /postingan/create maka arahkan ke PostinganController, method create, name nya adalah postingan.create
    Route::get('/postingan/create', [PostinganController::class, 'create'])->name('postingan.create');
    // route tipe kirim, jika user dirahkan ke url /postingan/simpan maka arahkan ke PostinganController, method simpan, name nya adalah postingan.simpan
    Route::post('/postingan/store', [PostinganController::class, 'store'])->name('postingan.store');
    // route tipe dapatkan, jika user dirahkan ke url /postingan/baca maka arahkan ke PostinganController, method baca, nama nya adalah postingan.baca
    Route::get('/postingan/read', [PostinganController::class, 'read'])->name('postingan.read');
    // route tipe dapatkan, jika user dirahkan ke url /postingan/edit maka kirimknan value postingan_id pake {postingan}, aku mengunakan fitur pengikatan route model agar aku bisa mengambil detail postingan berdasarkan postingan_id arahkan ke PostinganController, method edit, nama nya adalah postingan.edit
    Route::get('/postingan/edit/{postingan}', [PostinganController::class, 'edit'])->name('postingan.edit');
    // route tipe letakkan, jika user dirahkan ke url /postingan/ maka kirimkan value postingan_id nya agar aku bisa memperbarui detail postingan berdasarkan postingan_id menggunakan fitur pengikatan route model lalu arahkan ke PostinganController, method update, nama nya adalah postingan.update
    Route::put('/postingan/{postingan}', [PostinganController::class, 'update'])->name('postingan.update');
    // route tipe kirim, jika user dirahkan ke url /postingan/hancurkan maka arahkan ke PostinganController, method hancurkan, name nya adalah postingan.hancurkan
    Route::post('/postingan/destroy', [PostinganController::class, 'destroy'])->name('postingan.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /penceramah/create maka arahkan ke PenceramahController, method create, name nya adalah penceramah.create
    Route::get('/penceramah/create', [PenceramahController::class, 'create'])->name('penceramah.create');
    // route tipe kirim, jika user dirahkan ke url /penceramah/simpan maka arahkan ke PenceramahController, method simpan, name nya adalah penceramah.simpan
    Route::post('/penceramah/store', [PenceramahController::class, 'store'])->name('penceramah.store');
    // route tipe dapatkan, jika user dirahkan ke url /penceramah/baca maka arahkan ke PenceramahController, method baca, nama nya adalah penceramah.baca
    Route::get('/penceramah/read', [PenceramahController::class, 'read'])->name('penceramah.read');
    // route tipe dapatkan, jika user dirahkan ke url /penceramah/edit maka kirimknan value penceramah_id pake {penceramah}, aku mengunakan fitur pengikatan route model agar aku bisa mengambil detail penceramah berdasarkan penceramah_id arahkan ke PenceramahController, method edit, nama nya adalah penceramah.edit
    Route::get('/penceramah/edit/{penceramah}', [PenceramahController::class, 'edit'])->name('penceramah.edit');
    // route tipe letakkan, jika user dirahkan ke url /penceramah/ maka kirimkan value penceramah_id nya agar aku bisa memperbarui detail penceramah berdasarkan penceramah_id menggunakan fitur pengikatan route model lalu arahkan ke PenceramahController, method update, nama nya adalah penceramah.update
    Route::put('/penceramah/{penceramah}', [PenceramahController::class, 'update'])->name('penceramah.update');
    // route tipe kirim, jika user dirahkan ke url /penceramah/hancurkan maka arahkan ke PenceramahController, method hancurkan, name nya adalah penceramah.hancurkan
    Route::post('/penceramah/destroy', [PenceramahController::class, 'destroy'])->name('penceramah.destroy');
});


// auth di dapatkan dari Kernel.php
// hanya yang sudah login dan verifikasi di column email_verified_at yang bisa mengakses url berikut
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
    // route tipe dapatkan, jika user dirahkan ke url /doa lalu tangkap dan kirimkan doa_id ke DoaController, method show, name nya adalah doa.show
    Route::get('/doa/{doa_id}', [DoaController::class, 'show'])->name('doa.show');

    // route tipe dapatkan, jika user dirahkan ke url /kategori maka arahkan ke KategoriController, method index, name nya adalah kategori.index
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

    // route tipe dapatkan, jika user dirahkan ke url /postingan maka arahkan ke PostinganController, method index, name nya adalah postingan.index
    Route::get('/postingan', [PostinganController::class, 'index'])->name('postingan.index');
    // route tipe dapatkan, jika user dirahkan ke url /postingan/ maka kirimkan value slug_postingan pake {postingan:slug_postingan} karena aku tidak mengirim primary key, biarkan {postingan:slug_postingan} karena aku menggunakan fitur pengikatan route model, agar aku bisa mengambil detail_postingan berdasarkan slug_postingan, arahkan ke PostinganController, method show, nama nya adalah postingan.show
    Route::get('/postingan/{postingan:slug_postingan}', [PostinganController::class, 'show'])->name('postingan.show');

    // route tipe dapatkan, jika user dirahkan ke url /penceramah maka arahkan ke PenceramahController, method index, name nya adalah penceramah.index
    Route::get('/penceramah', [PenceramahController::class, 'index'])->name('penceramah.index');
});



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\KegiatanRutinController;
use App\Http\Controllers\KegiatanSekaliController;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PostinganController;
use App\Http\Controllers\PenceramahController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\JadwalAdzanController;
use App\Http\Controllers\Frontend\FrontendHomeController;
use App\Http\Controllers\Frontend\FrontendKegiatanController;
use App\Http\Controllers\Frontend\FrontendDoaController;
use App\Http\Controllers\Frontend\FrontendArtikelController;
use App\Http\Controllers\Frontend\FrontendPenceramahController;
use App\Http\Controllers\Frontend\FrontendDonasiController;
use App\Http\Controllers\Frontend\FrontendDonasiManualController;

// middleware untuk admin yang sudah login, sudah melengkapi profile nya berarti value detail_user nya sudah lengkap dan sudah verifikasi di column email_verified_at yang bisa mengakses url berikut
// auth di dapatkan dari App/Http/Kernel.php
// is_admin di dapatkan dari App/Providers/AuthServiceProvider.php, is_admin adalah fitur gate atau gerbang yang didapatkan dari security -> authorization, aku mengirim is_admin sebagai argument
// profile_sudah_lengkap adalah middleware yg di daftarkan di App\Http\Kernel
Route::middleware(['can:is_admin', 'profile_sudah_lengkap', 'auth', 'verified'])->group(function() {
    // home
    // route tipe tampilan, jika user diarahkan ke url berikut maka arahkan ke tampilan berikut, name nya adalah admin.home.index
    Route::view('/admin/home', 'admin.home.index')->name('admin.home.index');

    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin/create maka arahkan ke KegiatanRutinController, method create, name nya adalah kegiatan_rutin.create
    Route::get('/admin/kegiatan-rutin/create', [KegiatanRutinController::class, 'create'])->name('admin.kegiatan_rutin.create');
    // route tipe kirim, jika user dirahkan ke url /admin/kegiatan-rutin/simpan maka arahkan ke KegiatanRutinController, method store, name nya adalah kegiatan_rutin.store
    Route::post('/admin/kegiatan-rutin/store', [KegiatanRutinController::class, 'store'])->name('admin.kegiatan_rutin.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-rutin/baca maka arahkan ke KegiatanRutinController, method baca, nama nya adalah kegiatan_rutin.baca
    Route::get('/admin/kegiatan-rutin/read', [KegiatanRutinController::class, 'read'])->name('admin.kegiatan_rutin.read');
    // route tipe dapatkan, jika user dirahkan ke url /kegiatan-rutin maka arahkan ke KegiatanRutinController, method index, name nya adalah kegiatan_rutin.index
    Route::get('/admin/kegiatan-rutin', [KegiatanRutinController::class, 'index'])->name('admin.kegiatan_rutin.index');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-rutin/edit maka kirimkan value kegiatan_rutin_id nya agar aku bisa mengambil detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method edit, nama nya adalah kegiatan_rutin.edit
    Route::get('/admin/kegiatan-rutin/edit/{kegiatan_rutin_id}', [KegiatanRutinController::class, 'edit'])->name('admin.kegiatan_rutin.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/kegiatan-rutin/ maka kirimknan value kegiatan_id nya agar aku bisa memperbarui detail kegiatan_rutin berdasarkan kegiatan_id arahkan ke KegiatanRutinController, method update, nama nya adalah kegiatan_rutin.update
    Route::put('/admin/kegiatan-rutin/{kegiatan_id}', [KegiatanRutinController::class, 'update'])->name('admin.kegiatan_rutin.update');
    // route tipe kirim, jika user dirahkan ke url /admin/kegiatan-rutin/hancurkan maka arahkan ke KegiatanRutinController, method hancurkan, name nya adalah kegiatan_rutin.hancurkan
    Route::post('/admin/kegiatan-rutin/destroy', [KegiatanRutinController::class, 'destroy'])->name('admin.kegiatan_rutin.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-sekali maka arahkan ke KegiatanSekaliController, method index, name nya adalah kegiatan_sekali.index
    Route::get('/admin/kegiatan-sekali', [KegiatanSekaliController::class, 'index'])->name('admin.kegiatan_sekali.index');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-sekali/create maka arahkan ke KegiatanSekaliController, method create, name nya adalah kegiatan_sekali.create
    Route::get('/admin/kegiatan-sekali/create', [KegiatanSekaliController::class, 'create'])->name('admin.kegiatan_sekali.create');
    // route tipe kirim, jika user dirahkan ke url /admin/kegiatan-sekali/simpan maka arahkan ke KegiatanSekaliController, method simpan, name nya adalah kegiatan_sekali.simpan
    Route::post('/admin/kegiatan-sekali/store', [KegiatanSekaliController::class, 'store'])->name('admin.kegiatan_sekali.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-sekali/baca maka arahkan ke KegiatanSekaliController, method baca, nama nya adalah kegiatan_sekali.baca
    Route::get('/admin/kegiatan-sekali/read', [KegiatanSekaliController::class, 'read'])->name('admin.kegiatan_sekali.read');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kegiatan-sekali/edit maka kirimknan value kegiatan_sekali_id nya agar aku bisa mengambil detail kegiatan_sekali berdasarkan kegiatan_sekali_id arahkan ke KegiatanSekaliController, method edit, nama nya adalah kegiatan_sekali.edit
    Route::get('/admin/kegiatan-sekali/edit/{kegiatan_sekali_id}', [KegiatanSekaliController::class, 'edit'])->name('admin.kegiatan_sekali.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/kegiatan-sekali/ maka kirimknan value kegiatan_sekali_id nya agar aku bisa memperbarui detail kegiatan_sekali berdasarkan kegiatan_sekali_id arahkan ke KegiatanSekaliController, method update, nama nya adalah kegiatan_sekali.update
    Route::put('/admin/kegiatan-sekali/{kegiatan_sekali_id}', [KegiatanSekaliController::class, 'update'])->name('admin.kegiatan_sekali.update');
    // route tipe kirim, jika user dirahkan ke url /admin/kegiatan-sekali/hancurkan maka arahkan ke KegiatanSekaliController, method hancurkan, name nya adalah kegiatan_sekali.hancurkan
    Route::post('/admin/kegiatan-sekali/destroy', [KegiatanSekaliController::class, 'destroy'])->name('admin.kegiatan_sekali.destroy');



    // route tipe dapatkan, jika user dirahkan ke url /admin/doa/create maka arahkan ke DoaController, method create, name nya adalah doa.create
    Route::get('/admin/doa/create', [DoaController::class, 'create'])->name('admin.doa.create');
    // route tipe kirim, jika user dirahkan ke url /admin/doa maka arahkan ke DoaController, method simpan, name nya adalah doa.simpan
    Route::post('/admin/doa', [DoaController::class, 'store'])->name('admin.doa.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/doa/baca maka arahkan ke DoaController, method baca, nama nya adalah doa.baca
    Route::get('/admin/doa/read', [DoaController::class, 'read'])->name('admin.doa.read');
    // route tipe dapatkan, jika user dirahkan ke url /admin/doa maka arahkan ke DoaController, method index, name nya adalah doa.index
    Route::get('/admin/doa', [DoaController::class, 'index'])->name('admin.doa.index');
    // route tipe dapatkan, jika user dirahkan ke url berikut maka kirimkan value doa_id nya menggunakan fitur pengikatan route model agar aku bisa mengambil detail doa berdasarkan doa_id arahkan ke DoaController, method edit, nama nya adalah doa.edit
    Route::get('/admin/doa/edit/{doa}', [DoaController::class, 'edit'])->name('admin.doa.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/doa/ maka kirimknan value doa_id nya agar aku bisa memperbarui detail doa berdasarkan doa_id arahkan ke DoaController, method update, nama nya adalah doa.update
    Route::put('/admin/doa/{doa}', [DoaController::class, 'update'])->name('admin.doa.update');
    // route tipe kirim, jika user dirahkan ke url /admin/doa/hancurkan maka arahkan ke DoaController, method hancurkan, name nya adalah doa.hancurkan
    Route::post('/admin/doa/destroy', [DoaController::class, 'destroy'])->name('admin.doa.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /admin/kategori/create maka arahkan ke KategoriController, method create, name nya adalah kategori.create
    Route::get('/admin/kategori/create', [KategoriController::class, 'create'])->name('admin.kategori.create');
    // route tipe kirim, jika user dirahkan ke url /admin/kategori maka arahkan ke KategoriController, method simpan, name nya adalah kategori.simpan
    Route::post('/admin/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kategori/baca maka arahkan ke KategoriController, method baca, nama nya adalah kategori.baca
    Route::get('/admin/kategori/read', [KategoriController::class, 'read'])->name('admin.kategori.read');
    // route tipe dapatkan, jika user dirahkan ke url /admin/kategori maka arahkan ke KategoriController, method index, name nya adalah admin.kategori.index
    Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
    // route tipe dapatkan, jika user dirahkan ke url berikut maka kirimkan value slug_kategori pake {kategori:slug_kategori} karena aku tidak mengirim primary key, biarkan {kategori:slug_kategori} karena aku menggunakan fitur pengikatan route model, agar aku bisa mengambil detail kategori berdasarkan slug_kategori, arahkan ke KategoriController, method edit, nama nya adalah admin.kategori.edit
    Route::get('/admin/kategori/edit/{kategori:slug_kategori}', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/kategori/ maka kirimkan value kategori_id nya agar aku bisa memperbarui detail kategori berdasarkan kategori_id arahkan ke KategoriController, method update, nama nya adalah kategori.update
    Route::put('/admin/kategori/{kategori}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    // route tipe kirim, jika user dirahkan ke url /admin/kategori/hancurkan maka arahkan ke KategoriController, method hancurkan, name nya adalah kategori.hancurkan
    Route::post('/admin/kategori/destroy', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /admin/postingan/create maka arahkan ke PostinganController, method create, name nya adalah admin.postingan.create
    Route::get('/admin/postingan/create', [PostinganController::class, 'create'])->name('admin.postingan.create');
    // route tipe dapatkan, jika user dirahkan ke url /admin/postingan/baca maka arahkan ke PostinganController, method baca, nama nya adalah admin.postingan.baca
    Route::get('/admin/postingan/read', [PostinganController::class, 'read'])->name('admin.postingan.read');
    // route tipe dapatkan, jika user dirahkan ke url /admin/postingan/ maka kirimkan value slug_postingan pake {postingan:slug_postingan} karena aku tidak mengirim primary key, biarkan {postingan:slug_postingan} karena aku menggunakan fitur pengikatan route model, agar aku bisa mengambil detail_postingan berdasarkan slug_postingan, arahkan ke PostinganController, method show, nama nya adalah admin.postingan.show
    Route::get('/admin/postingan/{postingan:slug_postingan}', [PostinganController::class, 'show'])->name('admin.postingan.show');
    // route tipe dapatkan, jika user dirahkan ke url /admin/postingan maka arahkan ke PostinganController, method index, name nya adalah admin.postingan.index
    Route::get('/admin/postingan', [  PostinganController::class, 'index'])->name('admin.postingan.index');
    // rute tipe dapatkan, jika user diarahkan ke url /admin/postingan/cek-apakah-ada-kategori maka arahkan ke PostinganController, method cek_apakah_ada_kategori, namenya adalah admin.postingan.cek_apakah_ada_kategori
    Route::get('/admin/postingan/cek-apakah-ada-kategori', [PostinganController::class, 'cek_apakah_ada_kategori'])->name('admin.postingan.cek_apakah_ada_kategori');
    // route tipe kirim, jika user dirahkan ke url /admin/postingan/simpan maka arahkan ke PostinganController, method simpan, name nya adalah admin.postingan.simpan
    Route::post('/admin/postingan/store', [PostinganController::class, 'store'])->name('admin.postingan.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/postingan/edit maka kirimknan value postingan_id pake {postingan}, aku mengunakan fitur pengikatan route model agar aku bisa mengambil detail postingan berdasarkan postingan_id arahkan ke PostinganController, method edit, nama nya adalah admin.postingan.edit
    Route::get('/admin/postingan/edit/{postingan}', [PostinganController::class, 'edit'])->name('admin.postingan.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/postingan/ maka kirimkan value postingan_id nya agar aku bisa memperbarui detail postingan berdasarkan postingan_id menggunakan fitur pengikatan route model lalu arahkan ke PostinganController, method update, nama nya adalah admin.postingan.update
    Route::put('/admin/postingan/{postingan}', [PostinganController::class, 'update'])->name('admin.postingan.update');
    // route tipe kirim, jika user dirahkan ke url /admin/postingan/hancurkan maka arahkan ke PostinganController, method hancurkan, name nya adalah admin.postingan.hancurkan
    Route::post('/admin/postingan/destroy', [PostinganController::class, 'destroy'])->name('admin.postingan.destroy');

    // route tipe dapatkan, jika user dirahkan ke url /admin/penceramah/create maka arahkan ke PenceramahController, method create, name nya adalah admin.penceramah.create
    Route::get('/admin/penceramah/create', [PenceramahController::class, 'create'])->name('admin.penceramah.create');
    // route tipe kirim, jika user dirahkan ke url /admin/penceramah/simpan maka arahkan ke PenceramahController, method simpan, name nya adalah admin.penceramah.simpan
    Route::post('/admin/penceramah/store', [PenceramahController::class, 'store'])->name('admin.penceramah.store');
    // route tipe dapatkan, jika user dirahkan ke url /admin/penceramah/baca maka arahkan ke PenceramahController, method baca, nama nya adalah admin.penceramah.baca
    Route::get('/admin/penceramah/read', [PenceramahController::class, 'read'])->name('admin.penceramah.read');
    // route tipe dapatkan, jika user dirahkan ke url /admin/penceramah maka arahkan ke PenceramahController, method index, name nya adalah admin.penceramah.index
    Route::get('/admin/penceramah', [PenceramahController::class, 'index'])->name('admin.penceramah.index');
    // route tipe dapatkan, jika user dirahkan ke url /admin/penceramah/edit maka kirimknan value penceramah_id pake {penceramah}, aku mengunakan fitur pengikatan route model agar aku bisa mengambil detail penceramah berdasarkan penceramah_id arahkan ke PenceramahController, method edit, nama nya adalah admin.penceramah.edit
    Route::get('/admin/penceramah/edit/{penceramah}', [PenceramahController::class, 'edit'])->name('admin.penceramah.edit');
    // route tipe letakkan, jika user dirahkan ke url /admin/penceramah/ maka kirimkan value penceramah_id nya agar aku bisa memperbarui detail penceramah berdasarkan penceramah_id menggunakan fitur pengikatan route model lalu arahkan ke PenceramahController, method update, nama nya adalah admin.penceramah.update
    Route::put('/admin/penceramah/{penceramah}', [PenceramahController::class, 'update'])->name('admin.penceramah.update');
    // route tipe kirim, jika user dirahkan ke url /admin/penceramah/hancurkan maka arahkan ke PenceramahController, method hancurkan, name nya adalah admin.penceramah.hancurkan
    Route::post('/admin/penceramah/destroy', [PenceramahController::class, 'destroy'])->name('admin.penceramah.destroy');

    // route tipe dapatkan, jika user diarahkan ke url /admin/donasi maka arahkan ke DonasiController, method index, name nya adalah admin.donasi.index
    Route::get('/admin/donasi', [DonasiController::class, 'index'])->name('admin.donasi.index');

    // route tipe kirim, jika user diarahkan ke url berikut maka tangkap dan kirim argument lalu arahkan ke DonasiController, method ekspor_pdf, name nya adalah admin.donasi.ekspor_pdf
    Route::post('/admin/donasi/ekspor_pdf/{tanggal_awal}/{tanggal_akhir}', [DonasiController::class, 'ekspor_pdf'])->name('admin.donasi.ekspor_pdf');
});

// auth di dapatkan dari Kernel.php
// hanya yang sudah login, profile nya sudah lengkap di detail_user nya dan verifikasi di column email_verified_at yang bisa mengakses url atau route berikut
// route group middleware, untuk yang sudah login, profile nya sudah lengkap di detail_user nya dan verifikasi di colum email_diverifikasi_pada yang bisa mengakses route berikut
Route::middleware(['auth', 'profile_sudah_lengkap', 'verified'])->group(function() {
    // edit profile
    // route tipe dapatkan, jika user di url /edit-profile maka arahkan ke EditProfileController, method edit, name nya adalah edit_profile
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit_profile')
    // untuk mencegah jika dia di url /edit-profile maka dia akan dia akan ke url /edit-profile lagi
    // tanpaMiddleware profile_sudah_lengkap
    ->withoutMiddleware('profile_sudah_lengkap');
    // route tipe kirim, jika user di url /edit-profile maka arahkan ke EditProfileController, method perbarui, name nya adalah perbarui_profile
    Route::post('/edit-profile', [EditProfileController::class, 'update'])->name('update_profile')
    // untuk mencegah jika dia di url /edit-profile maka dia akan dia akan ke url /edit-profile lagi
    // tanpaMiddleware profile_sudah_lengkap
    ->withoutMiddleware('profile_sudah_lengkap');
    // Route tipe post, jika user diarakan ke url berikut, maka arahkan ke EditProfileController dan method perbarui_password, name nya adalah edit_profile.perbarui_password
    Route::post('/edit-profile/update-password', [EditProfileController::class, 'update_password'])->name('edit_profile.update_password');    

    // rute tipe kirim jika user diarahkan ke url /frontend/artikel/komentar maka arahkan ke FrontendArtikelController, method simpan_komentar, name nya adalah frontend.artikel.simpan_komentar
    Route::post('/frontend/artikel/komentar', [FrontendArtikelController::class, 'simpan_komentar'])->name('frontend.artikel.simpan_komentar');

    // route tipe dapatkan, jika user diarahkan ke url berikut lalu kirimkan value detail_postingan, column postingan_id lalu gunakan fitur pengikatan route model agar aku bisa mengambil detail_postingan lalu arahkan ke FrontendArtikelController, method halaman_semua_komentar, namenya adalah frontend.artikel.halaman_semua_komentar
     Route::get('/frontend/artikel/halaman-semua-komentar/{postingan}', [FrontendArtikelController::class, 'halaman_semua_komentar'])->name('frontend.artikel.halaman_semua_komentar');

    // route tipe dapatkan, jika user diarahkan ke url /frontend/artikel/read-semua-komentar lalu kirimkan value detail_postingan, column postingan_id lalu gunakan fitur pengikatan route model untuk mengambil detail_postingan maka arahkan ke PostinganController, method read_semua_komentar, namenya adalah frontend.artikel.read_semua_komentar
    Route::get('/frontend/artikel/read-semua-komentar/{postingan}', [FrontendArtikelController::class, 'read_semua_komentar'])->name('frontend.artikel.read_semua_komentar');

    // route tipe dapatkan, jika user dirahkan ke url /donasi/detail maka kirimkan detail_donasi, column donasi_id karena aku gunakan fitur pengikatan route model dan aku juga mengirimkan snapToken lalu aku arahkan ke DonasiController, method detail, name nya adalah donasi.detail
    Route::get('/donasi/detail/{donasi}/{snapToken}', [DonasiController::class, 'detail'])->name('donasi.detail');

    // route tipe dapatkan, jika user dirahkan ke url /donasi/create maka arahkan ke DonasiController, method create, name nya adalah admin.donasi.create
    Route::get('donasi/create', [DonasiController::class, 'create'])->name('admin.donasi.create');

    // route tipe kirim, jika user dirahkan ke url berikut maka arahkan ke DonasiController, method simpan, name nya adalah donasi.simpan
    Route::post('donasi/store', [DonasiController::class, 'store'])->name('donasi.store');

    // route tipe dapatkan, jika user dirahkan ke url berikut maka arahkan ke DonasiController, method menunggu_pembayaran, name nya adalah donasi.menunggu_pembayaran
    Route::get('donasi/menunggu-pembayaran', [DonasiController::class, 'menunggu_pembayaran'])->name('donasi.menunggu_pembayaran');

    // route tipe dapatkan, jika user dirahkan ke url donasi-manual/create maka arahkan ke DonasiManualController, method create, name nya adalah donasi_manual.create
    Route::get('donasi-manual/create', [FrontendDonasiManualController::class, 'create'])->name('donasi_manual.create');

    // route tipe kirim, jika user dirahkan ke url berikut maka arahkan ke FrontendDonasiManualController, method simpan, name nya adalah donasi_manual.simpan
    Route::post('donasi-manual/store', [FrontendDonasiManualController::class, 'store'])->name('donasi_manual.store');
});


// AKU LETAKKAN ROUTE NYA DIBAWAH AGAR TIDAK KENA TIMPA
// panggil routes/auth.php
// membutuhkan directori yang sma digabung auth.php
require __DIR__.'/auth.php';

// route tipe alihkan, jika user di url awal maka arahkan ke url /login yang berada di routes/auth
Route::redirect('/', '/login');




// route tipe dapatkan, jika user di url /jadwal-adzan maka panggil JadwalAdzanController, method index, name nya adalah jadwal_sholat.index
Route::get('/jadwal-adzan', [JadwalAdzanController::class, 'index'])->name('jadwal_sholat.index');
// rute tipe dapatkan, jika user diarahkan ke url /frontend maka arahkan ke FrontendHomeController, method index, name nya adalah frontend.index

// rute tipe dapatkan, jika user diarahkan ke url /frontend maka arahkan ke FrontendHomeController, method index, name nya adalah frontend.index
Route::get('/frontend', [FrontendHomeController::class, 'index'])->name('frontend.index');

// Route tipe dapatkan, jika user diarahkan ke url /kegiatan maka arahkan ke FrontendKegiatanController, method index, name nya adalah frontend.kegiatan.index
Route::get('/kegiatan', [FrontendKegiatanController::class, 'index'])->name('frontend.kegiatan.index');

// route tipe dapatkan, jika user dirahkan ke url /doa/simpan-data-doa-dari-api-external maka arahkan ke DoaController, method simpan_data_doa_dari_api_external, nama route nya adalah doa.simpan_data_doa_dari_api_external
Route::get('/doa/simpan-data-doa-dari-api-external', [DoaController::class, 'simpan_data_doa_dari_api_external'])->name('doa.simpan_data_doa_dari_api_external');
// Route tipe dapatkan, jika user diarahkan ke url /frontend/doa maka arahkan ke FrontendDoaController, method index, name nya adalah frontend.doa.index
Route::get('/frontend/doa', [FrontendDoaController::class, 'index'])->name('frontend.doa.index');
// Route tipe dapatkan, jika user diarahkan ke url /frontend/doa/read maka arahkan ke FrontendDoaController, method read, name nya adalah frontend.doa.read
Route::get('/frontend/doa/read', [FrontendDoaController::class, 'read'])->name('frontend.doa.read');

// route tipe dapatkan, jika user dirahkan ke url berikut maka kirimkan value slug_postingan pake {postingan:slug_postingan} karena aku tidak mengirim primary key, biarkan {postingan:slug_postingan} karena aku menggunakan fitur pengikatan route model, agar aku bisa mengambil detail_postingan berdasarkan slug_postingan, arahkan ke FrontendArtikelController, method show, nama nya adalah postingan.show
Route::get('/frontend/artikel/{postingan:slug_postingan}', [FrontendArtikelController::class, 'show'])->name('frontend.artikel.show');
// Route tipe dapatkan, jika user diarahkan ke url /frontend/artikel maka arahkan ke FrontendArtikelController, method index, name nya adalah frontend.artikel.index
Route::get('/frontend/artikel', [FrontendArtikelController::class, 'index'])->name('frontend.artikel.index');
// route tipe dapatkan, jika user diarahkan ke url berikut lalu kirimkan value detail_postingan, column postingan_id lalu arahkan ke FrontendArtikelController, method detail_komentar_terbaru, namenya adalah frontend.artikel.detail_komentar_terbaru
Route::get('/frontend/artikel/detail-komentar-terbaru/{postingan_id}', [FrontendArtikelController::class, 'detail_komentar_terbaru'])->name('frontend.artikel.detail_komentar_terbaru');
// Route tipe dapatkan, jika user diarahkan ke url /frontend/penceramah maka arahkan ke FrontendPenceramahController, method index, name nya adalah frontend.penceramah.index
Route::get('/frontend/penceramah', [FrontendPenceramahController::class, 'index'])->name('frontend.penceramah.index');

// ada route fitur midtrans yang aku tulis routes/api
// route tipe dapatkan, jika user dirahkan ke url /admin/donasi/baca maka arahkan ke DonasiController, method baca, nama nya adalah admin.donasi.baca
Route::get('/admin/donasi/read', [DonasiController::class, 'read'])->name('admin.donasi.read');
// route tipe dapatkan, jika user dirahkan ke url /frontend/donasi/create maka arahkan ke FrontendDonasiController, method create, name nya adalah frontend.donasi.create
Route::get('/frontend/donasi/create', [FrontendDonasiController::class, 'create'])->name('donasi.create');
// route tipe kirim, jika user diarahkan ke url donasi/ubah-periode, arahkan ke DonasiController, method ubah_periode, name nya adalah admin/donasi.ubah_periode
Route::post('donasi/ubah-periode', [DonasiController::class, 'ubah_periode'])->name('admin.donasi.ubah_periode');


// route tipe dapatkan, jika user dirahkan ke url berikut lalu tangkap dan kirimkan doa_id lalu gunakan fitur pengikatan route model lalu ke DoaController, method show, name nya adalah berikut
Route::get('doa/{doa}', [DoaController::class, 'show'])->name('doa.show');
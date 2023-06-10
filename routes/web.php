<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// panggil routes/auth.php
// membutuhkan directori yang sma digabung auth.php
require __DIR__.'/auth.php';

// url
// route tipe dapatkan, jika user di url awal maka jalankan fungsi
Route::get('/', function () {
    // kembali alihkan welcome
    return view('welcome');
});

// route tipe dapatkan, jika user di url dashboard maka jalankan fungsi
Route::get('/dashboard', function () {
    // kembalikkan ke tampilan dashboard
    return view('dashboard');
})
// user harus login dulu dan harus verifikasi email, name nya adalah dashboard
// middleware adalah perangkat tengah, middleware berada diantara url dan controller
->middleware(['auth', 'verified'])->name('dashboard.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



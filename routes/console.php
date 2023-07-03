<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes Rute-rute konsol
|--------------------------------------------------------------------------
|
| File ini adalah tempat Anda dapat menentukan semua perintah konsol berbasis Penutupan. Setiap Penutupan terikat pada instance perintah yang memungkinkan pendekatan sederhana untuk berinteraksi dengan metode IO setiap perintah.
|
*/

// tukang, perintah, inspirasi, jalankan fungsi
Artisan::command('inspire', function () {
    // class console, koment, inspirasi::quote
    $this->comment(Inspiring::quote());
})
// tujuan nya sebagai berikut
->purpose('Tampilkan kutipan yang menginspirasi');

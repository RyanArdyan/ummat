<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Semua route api di awali /api jadi jika anda membuat url /user maka untuk testing di postman, anda harus menulis /api/user.

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Kalau disimpan disini maka aku tidak perlu membuat csrf token
// route setelah user selesai melakukan pembayaran
// route tipe kirim, jika user diarahkan ke url /midtrans-panggilanBalik, panggil OrderPengendali, method panggilanBalik
Route::post('/order/midtrans-callback', [OrderController::class, 'callback']);


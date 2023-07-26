<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Semua route api di awali /api jadi jika anda membuat url /user maka untuk testing di postman, anda harus menulis /api/user.

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

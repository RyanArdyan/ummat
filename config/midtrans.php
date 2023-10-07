<?php

// kembalikkan array
return [
    // key merchant_id memanggil constanta MIDTRANS_MERCHANT_ID di file .env
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    // panggil constanta MIDTRANS_MERCHANT_ID di file .env
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    // panggil constanta MIDTRANS_SERVER_ID di file .env
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    // key is_production memanggil constanta MIDTRANS_IS_PRODUCTION di file .env
    'is_production' => env('MIDTRANS_IS_PRODUCTION')
];
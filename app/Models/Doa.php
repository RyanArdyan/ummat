<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// gunakan UUID sebagai column primary key
// use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Doa extends Model
{
    use HasFactory;
    // gunakan UUID sebagai column primary key
    // use HasUuids;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'doa';
    protected $table = 'doa';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'doa_id';
    protected $primaryKey = 'doa_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];
}

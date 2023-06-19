<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'kegiatan';
    protected $table = 'kegiatan';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'kegiatan_id';
    protected $primaryKey = 'kegiatan_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];
}

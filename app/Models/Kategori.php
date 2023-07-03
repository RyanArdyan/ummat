<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// import atau gunakan, jadi kolom UUID akan terissi secara otomatis ketika aku menambah baris data baru
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kategori extends Model
{
    // Gunakan MemilikiUuIds
    use HasUuids, HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'kategori';
    protected $table = 'kategori';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'kategori_id';
    protected $primaryKey = 'kategori_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];
}

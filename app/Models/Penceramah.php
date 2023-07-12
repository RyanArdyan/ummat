<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penceramah extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'penceramah';
    protected $table = 'penceramah';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'penceramah_id';
    protected $primaryKey = 'penceramah_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];
}

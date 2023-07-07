<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// import atau gunakan 
use Illuminate\Database\Eloquent\Concerns\HasUuids;
// rich text editor dari https://github.com/amaelftah/laravel-trix
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Postingan extends Model
{
    // Gunakan MemilikiUuIds
    use HasUuids, HasFactory;
    // rich text editor dari https://github.com/amaelftah/laravel-trix
    use HasTrixRichText;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'postingan';
    protected $table = 'postingan';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'postingan_id';
    protected $primaryKey = 'postingan_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];
}

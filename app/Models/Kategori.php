<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// relasi banyak ke banyak atau many to many
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kategori extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'kategori';
    protected $table = 'kategori';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'kategori_id';
    protected $primaryKey = 'kategori_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    // relasi banyak ke banyak atau many to many
    // 1 kategori punya banyak postingan
    public function postingan(): BelongsToMany
    {
        // argument pertama yang diteruskan ke method ini adalah nama kelas model terkait
        // argument kedua yang diteruskan ke method ini adalah nama table dari table perantara relasi
        // argument ketiga adalah primary key dari table kategori
        // argument keempat adalah primary key dari table postingan
        return $this->belongsToMany(Postingan::class, 'postingan_kategori', 'kategori_id', 'postingan_id');
    }
}
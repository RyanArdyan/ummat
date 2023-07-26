<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'komentar';
    protected $table = 'komentar';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'komentar_id';
    protected $primaryKey = 'komentar_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap komentar maka detail_user dan semua balasan juga ikut terbawa, jadi aku hanya akan mengambil value column user_id dan name
    // lindungi $dengan relasi method user, column user_id dan name dan method balasan
    protected $with = ["user:user_id,name", "balasan"];

    // relasi
    // belongs to / satu komentar milik  atau ditulis 1 user
    public function user() 
    {
        // argumen pertama adalah berelasi dengan models/user
        // argumen kedua adalah foreign key di table komentar
        // argumen ketiga adalah primary key di table user
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // // belongs to / satu komentar milik 1 postingan
    public function postingan() 
    {
        // argumen pertama adalah berelasi dengan models/postingan
        // argumen kedua adalah foreign key di table komentar
        // argumen ketiga adalah primary key di table postingan
        return $this->belongsTo(Postingan::class, 'postingan_id', 'postingan_id');
    }


    // 1 komentar dapat memiliki banyak balasan
    public function balasan()
    {
        // argument pertama adalah berelasi dengan models/komentar
        // argument kedua adalah foreign key di table komentar
        return $this->hasMany(Komentar::class, 'parent_id');
    }
}

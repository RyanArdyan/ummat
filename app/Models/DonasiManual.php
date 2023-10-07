<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiManual extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'donasi_manual';
    protected $table = 'donasi_manual';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'donasi_manual_id';
    protected $primaryKey = 'donasi_manual_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil value detail_donasi_manual maka value detail_user juga ikut terbawa, jadi aku hanya akan mengambil value column user_id dan name
    // lindungi $dengan relasi user
    protected $with = ["user:user_id,name"];

    // relasi
    // belongs to / satu donasi_manual milik atau di donasi_manualkan oleh 1 user dan 1 user bisa melakukan banyak donasi_manual
    public function user()
    {
        // argumen pertama adalah berelasi dengan models/user
        // argumen kedua adalah foreign key di table donasi_manual
        // argumen ketiga adalah primary key di table user
        // kembalikkan class Donasi_manual milik (User::kelas, 'user_id', 'user_id')
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

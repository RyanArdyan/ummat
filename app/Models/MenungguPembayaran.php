<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenungguPembayaran extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'menunggu_pembayaran';
    protected $table = 'menunggu_pembayaran';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'menunggu_pembayaran_id';
    protected $primaryKey = 'menunggu_pembayaran_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil menunggu_pembayaran maka detail_donasi juga ikut terbawa, jadi aku hanya akan mengambil value column donasi_id dan lain-lain.
    // lindungi $dengan relasi donasi
    protected $with = ["donasi:donasi_id,user_id,jumlah_donasi,pesan_donasi,status"];

    // relasi
    // 1 menunggu_pembayaran milik 1 donasi
    public function donasi()
    {
        // argumen pertama adalah berelasi dengan models/Donasi
        // argumen kedua adalah foreign key di table menunggu_pembayaran
        // argumen ketiga adalah primary key di table donasi
        // kembalikkan class Donasi milik (User::kelas, 'user_id', 'user_id')
        return $this->hasOne(Donasi::class, 'donasi_id', 'donasi_id');
    }    
}

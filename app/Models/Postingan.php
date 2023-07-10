<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// relasi banyak ke banyak atau many to many
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postingan extends Model
{
    use HasFactory;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'postingan';
    protected $table = 'postingan';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'postingan_id';
    protected $primaryKey = 'postingan_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    // Misalnya, saat Commentmodel diperbarui, Anda mungkin ingin secara otomatis "menyentuh" updated_at​​stempel waktu kepemilikan Postsehingga diatur ke tanggal dan waktu saat ini. 
    protected $touches = ["kategori"];

    // relasi banyak ke banyak atau many to many
    // 1 postingan punya banyak kategori
    public function kategori(): BelongsToMany
    {
        // argument pertama yang diteruskan ke method ini adalah nama kelas model terkait
        // argument kedua yang diteruskan ke method ini adalah nama table dari table perantara relasi
        // argument ketiga adalah primary key dari table postingan
        // argument keempat adalah primary key dari table kategori
        return $this->belongsToMany(Kategori::class, 'postingan_kategori', 'postingan_id', 'kategori_id')
        // Tabel perantara Anda akan memiliki created_at stempel updated_at waktu yang secara otomatis dikelola oleh Eloquent, panggil withTimestampsmetode saat menentukan hubungan:
        ->withTimestamps();
    }


    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap postingan maka detail_user juga ikut terbawa, jadi aku hanya akan mengambil value column user_id dan name
    // lindungi $dengan relasi user dan penyuplai
    protected $with = ["user:user_id,name"];

    // relasi
    // belongs to / satu postingan milik 1 user
    public function user() :BelongsTo
    {
        // argumen pertama adalah berelasi dengan models/user
        // argumen kedua adalah foreign key di table postingan
        // argumen ketiga adalah primary key di table user
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

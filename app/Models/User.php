<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // nama table bawaan nya adalah jamak versi inggris maka nya aku mengubah nya
    // lindungi $meja = 'users';
    protected $table = 'users';
    // kunci utama bawaan nya adalah id makanya aku mengubah nya
    // lindungi $utamaKunci = 'user_id';
    protected $primaryKey = 'user_id';
    // agar fitur buat dan update data secara massal berhasil
    // lindungi $penjaga = [];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 1 user dapat menulis banyak komentar
    // models user berelasi dengn model/komentar
    public function komentar()
    {   
        // argument pertama adalah relasi nya dengan models/komentar
        // argument kedua pada hasMany adalah foreign key di table child atau table yg punya column foreign key yaitu table komentar, colunn user_id
        // 1 user dapat memiliki atau menulis banyak komentar, $this berarti memanggil Models/User, komentar terbaru akan tampil sebagai urutnan pertama
        // kembalikkan $ini->memilikiBanyak(Komentar::class, 'user_id')->dipesanOleh('diperbarui_pada', 'menurun')
        return $this->hasMany(Komentar::class, 'user_id')->orderBy('updated_at', 'desc');
    }

    // 1 user dapat melakukan banyak donasi
    // models user berelasi dengn model/donasi
    public function donasi()
    {   
        // argument pertama adalah relasi nya dengan models/donasi
        // argument kedua pada hasMany adalah foreign key di table child atau table yg punya column foreign key yaitu table donasi, column user_id
        // 1 user dapat memiliki atau melakukan banyak donasi dan donasi terbaru akan muncul dipaling atas, $this berarti memanggil Models/User
        // kembalikkan $ini->memilikiBanyak(donasi::class, 'user_id')->dipesanOleh('diperbarui_pada', menurun)
        return $this->hasMany(Donasi::class, 'user_id')->orderBy('updated_at', 'desc');
    }

    // 1 user dapat menulis banyak postingan
    // models user berelasi dengn model/postingan
    public function postingan()
    {   
        // argument pertama adalah relasi nya dengan models/postingan
        // argument kedua pada hasMany adalah foreign key di table child atau table yg punya column foreign key yaitu table postingan, column user_id
        // 1 user dapat memiliki atau menulis banyak postingan dan postingan terbaru akan muncul dipaling atas, $this berarti memanggil Models/User
        // kembalikkan $ini->memilikiBanyak(Postingan::class, 'user_id')->dipesanOleh('diperbarui_pada', menurun)
        return $this->hasMany(Postingan::class, 'user_id')->orderBy('updated_at', 'desc');
    }
}

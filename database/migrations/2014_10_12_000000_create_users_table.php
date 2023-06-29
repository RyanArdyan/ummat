<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // skema buat table penguna2x, jalankan fungsi, cetakBiru, $meja
        Schema::create('users', function (Blueprint $table) {
            // buat tipe data uuid yang primary key
            $table->uuid('user_id');
            // jadikan column user_id sebagai kunci utama
            // $table->utama('user_id')
            $table->primary('user_id');
            // tipe enum, akan memberikan pilihan yang harus dipilih yaitu 1 berarti admin, 0 berarti jamaah, bawaan nya 0 berarti jamaah
            $table->enum('is_admin', [1, 0])->default("0");
            // tipe varchar, kolom foto, boleh ksoong karena ketika registrasi, user tidak harus memasukkan foto ya
            $table->string('foto')->nullable();
            // tipe string, kolom nama, value kolom nama harus unik atau tidak boleh sama
            $table->string('name')->unique();
            // tipe string, kolom email, harus unik atau tidak boleh sama
            $table->string('email')->unique();
            // tipe char adalah tipe yg panjang nya tetap, boleh kosong
            // tipe char, kolom nik, maksimal panjang nya adalah 18
            $table->char('nik', 18)->nullable();
            // tipe sting, kolom nomor_wa, value nya harus unik atau tidak boleh sama
            $table->string('nomor_wa')->unique()->nullable();
            // tipe tanggal, kolom tgl_lahir, boleh kosong
            $table->date('tgl_lahir')->nullable();
            // tipe enum adalah tipe yang memberikan pilihan
            // tipe enum, kolom jenis_kelamin, value nya adalah laki-laki dan perempuan, boleh kosong.
            $table->enum("jenis_kelamin", ["laki-laki", "perempuan"])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            // tipe string, kolom password
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

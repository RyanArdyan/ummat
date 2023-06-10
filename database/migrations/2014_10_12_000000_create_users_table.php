<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // skema buat table penguna2x, jalankan fungsi, cetakBiru, $meja
        Schema::create('users', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('user_id');
            // defaultnya adalah 0, 0 berarti jamaah, 1 berarti admin
            $table->tinyInteger('is_admin')->default(0);
            // tipe varchar, kolom foto, boleh ksoong karena ketika registrasi, user tidak harus memasukkan foto ya
            $table->string('foto')->nullable();
            // tipe string, kolom nama, value kolom nama harus unik atau tidak boleh sama
            $table->string('nama')->unique();
            // tipe string, kolom email, harus unik atau tidak boleh sama
            $table->string('email')->unique();
            // tipe char adalah tipe yg panjang nya tetap, boleh kosong
            // tipe char, kolom nik, panjang nya adalah 16
            $table->char('nik', 16)->nullable();
            // tipe sting, kolom nomor_wa, value nya harus unik atau tidak boleh sama
            $table->string('nomor_wa')->unique()->nullable();
            // tipe tanggal, kolom tgl_lahir
            $table->date('tgl_lahir');
            // tipe enum adalah tipe yang memberikan pilihan
            // tipe enum, kolom jenis_kelamin, value nya adalah laki-laki dan perempuan
            $table->enum("jenis_kelamin", ["laki-laki", "perempuan"]);
            $table->timestamp('email_verified_at')->nullable();
            // tipe string, kolom password
            $table->string('password');
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

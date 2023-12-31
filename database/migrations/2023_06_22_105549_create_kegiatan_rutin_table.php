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
        // skema buat table kegiatan_rutin, jalankan fungsi berikut, Cetakbiru $meja
        Schema::create('kegiatan_rutin', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('kegiatan_rutin_id');
            // tipe string, column nama_kegiatan, value nya harus unique atau tidak boleh sama
            $table->string('nama_kegiatan')->unique();
            // tipe string, column gambar_kegiatan
            $table->string('gambar_kegiatan');
            // tipe enum di mysql adalah nilai data yang akan digunakan ketika penginputan data, sehingga ketika ada value yang tidak sesuai dengan nilai yang sudah didefinisikan sebelumnya, maka akan menghasilkan error.
            // tipe enum, column hari
            $table->enum("hari", ["Senin", "Selasa", "Rabu", "Kamis", "Jum\"at", 'Sabtu', 'Minggu']);
            // tipe jam, kolom jam_mulai
            $table->time('jam_mulai');
            // tipe jam, kolom jam_selesai
            $table->time("jam_selesai");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_rutin');
    }
};

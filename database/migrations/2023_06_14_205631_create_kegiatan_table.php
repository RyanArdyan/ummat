<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi
     */
    public function up(): void
    {
        // skema buat table kegiatan, jalankan fungsi berikut, Cetakbiru $meja
        Schema::create('kegiatan', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('kegiatan_id');
            // tipe string, column nama_kegiatan
            $table->string('nama_kegiatan');
            // tipe string, column gambar_kegiatan
            $table->string('gambar_kegiatan');
            // tipe date, column tanggal
            $table->date('tanggal');
            // tipe jam, kolom jam_mulai
            $table->time('jam_mulai');
            // tipe jam, kolom jam_selesai
            $table->time("jam_selesai");
            // tipe enum adalah tipe yang memberikan pilihan, kolom tipe_kegiatan, Pilihannya adalah "Kegiatan Rutin" dan "Kegiatan Sekali"
            $table->enum("tipe_kegiatan", ["Kegiatan Rutin", "Kegiatan Sekali"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};

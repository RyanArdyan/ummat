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
        // skema buat table penceramah, jalankan fungsi, cetakBiru, $meja
        Schema::create('penceramah', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('penceramah_id');
            // tipe string, column nama_penceramah, value nya harus unik atau tidak boleh sama
            $table->string('nama_penceramah')->unique();
            // tipe string, column foto_penceramah
            $table->string('foto_penceramah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penceramah');
    }
};

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
        // skema buat table doa jalankan fngsi berikut (Cetakbirut, $meja)
        Schema::create('doa', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('doa_id');
            // tipe string, column nama_doa, harus unique()
            $table->string('nama_doa')->unique();
            // tipe string, column bacaan_arab, harus unique()
            $table->string('bacaan_arab')->unique();
            // tipe string, column bacaan_latin, harus unique()
            $table->string('bacaan_latin')->unique();
            // tipe string, column arti_doanya, harus unique()
            $table->string('arti_doanya')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doa');
    }
};

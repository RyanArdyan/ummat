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

            // // metode uuid membuat kolom uuid
            // // $meja, tipe uuid, column doa_id, merupakan primary key
            // $table->uuid("doa_id")->primary();
            // tipe string, column nama_doa, harus unique()
            $table->string('nama_doa')->unique();
            // tipe string, column bacaan_arab, harus unique()
            $table->text('bacaan_arab');
            // tipe string, column bacaan_latin, harus unique()
            $table->text('bacaan_latin');
            // tipe text, column arti_doanya
            $table->text('arti_doanya');
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

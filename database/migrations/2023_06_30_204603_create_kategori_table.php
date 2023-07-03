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
        Schema::create('kategori', function (Blueprint $table) {
            // buat tipe data uuid
            $table->uuid('kategori_id');
            // jadikan kategori_id sebagai primary key
            $table->primary('kategori_id');
            // tipe varchar, column nama_kategori, harus unik
            $table->string('nama_kategori')->unique();
            $table->string('slug_kategori');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};

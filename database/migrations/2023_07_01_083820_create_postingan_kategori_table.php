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
        // skema buat table postingan_kategori, jalankan fungsi, cetakBiru, $meja
        Schema::create('postingan_kategori', function (Blueprint $table) {
            // buat tipe data uuid
            $table->uuid('postingan_kategori_id');
            // jadikan postingan_kategori_id sebagai primary key
            $table->primary('postingan_kategori_id');
            $table->foreignUuid('postingan_id')->constrained('postingan')
                ->references('postingan_id');
            $table->foreignUuid('kategori_id')->constrained('kategori')
                ->references('kategori_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postingan_kategori');
    }
};

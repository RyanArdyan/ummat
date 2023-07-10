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
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('postingan_kategori_id');
            $table->foreignId('postingan_id')->constrained('postingan')
            ->references('postingan_id')
            ->onUpdate('cascade')
            // ketika di hapus mengalir, jadi jika aku hapus postingan maka semua postingan_kategori terkait nya juga akan terhapus
            ->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori')
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

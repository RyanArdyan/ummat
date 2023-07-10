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
        Schema::create('postingan', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('postingan_id');
            // foreign key atau kunci asing, relasinya adalah 1 produk milik 1 user dan 1 user memiliki banyak produk
            // buat foreign k
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('user_id')->constrained('users')
                // referensi column user_id milik table users
                ->references('user_id')
                ->onUpdate('cascade')
                // ketika di hapus mengalir
                ->onDelete('cascade');
            // tipe varchar, column judul_postingan harus unique
            $table->string('judul_postingan')->unique();
            $table->string('slug_postingan');
            $table->string('gambar_postingan');
            // tipe text, column konten
            $table->text('konten_postingan');
            $table->dateTime('dipublikasi_pada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postingan');
    }
};

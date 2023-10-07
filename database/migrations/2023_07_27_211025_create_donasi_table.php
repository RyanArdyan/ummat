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
        // skema buat table donasi, jalankan fungsi cetakBiru, $meja
        Schema::create('donasi', function (Blueprint $table) {
            // buat tipe data uuid yang dan primary key atau kunci utama
            $table->uuid('donasi_id')->primary();
            // foreign key atau kunci asing, relasinya adalah 1 donasi milik atau di donasi 1 user dan 1 user memiliki banyak donasi atau bisa 1 user bisa melakkan banyak donasi
            // foreign artinya asing, constrained artinya dibatasi, referensi nya adalah table users
            $table->foreignId('user_id')->constrained('users')
                // referensi nya adalah column user_id milik table users
                ->references('user_id')
                // ketika di hapus mengalir, jadi ketika aku hapus detail_user maka donasi terkait nya juga ikut terhapus
                ->onDelete('cascade')
                // ketika diperbarui mengalir
                ->onUpdate('cascade');
            // tipe integer, column jumlah_donasi
            $table->integer('jumlah_donasi');
            // tipe string, column pesan_donasi
            $table->string('pesan_donasi');
            // tipe enum adalah tipe yang value nya harus dipilih sesuai pilihan yg disediakan
            // tipe enum, column status, pilihan value nya adalah "Sudah Bayar" atau "Belum Bayar", bawaan nya adalah "Belum Bayar";
            $table->enum('status', ["Sudah Bayar", "Belum Bayar"])->default("Belum Bayar");
            // nullable karena aku ada menambah data di DatabaseSeeder
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->time('jam_kadaluarsa')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};

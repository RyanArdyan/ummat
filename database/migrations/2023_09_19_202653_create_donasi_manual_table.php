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
        // skema buat table donasi_manual jalankan fungsi Cetakbiru, $meja
        Schema::create('donasi_manual', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('donasi_manual_id');
            // foreign key atau kunci asing, relasinya adalah 1 donasi_manual milik atau di donasikan oleh 1 user dan 1 user memiliki banyak donasi_manual atau 1 user bisa melakukan banyak donasi_manual
            // foreign artinya asing, constrained artinya dibatasi, referensi nya adalah table users
            $table->foreignId('user_id')->constrained('users')
                // referensi nya adalah column user_id milik table users
                ->references('user_id')
                // ketika di hapus mengalir, jadi ketika aku hapus detail_user maka donasi_manual terkait nya juga ikut terhapus
                ->onDelete('cascade')
                // ketika diperbarui mengalir
                ->onUpdate('cascade');
            // tipe string, column foto_bukti
            $table->string('foto_bukti');
            $table->integer('jumlah_donasi');
            $table->string('pesan_donasi');
            // tipe string, column nomor_wa, panjang maksimal nya adalah 20
            $table->string('nomor_wa', 20);
            // tipe enum, tipe enum adalah tipe yg value nya hanya bisa diisi dengan value yg disediakan
            $table->enum('status', ['Belum Cek', 'Benar'])->default('Belum Cek');
            $table->enum('tipe_pembayaran', ['Gopay', 'Bank BNI']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi_manual');
    }
};

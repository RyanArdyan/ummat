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
            // buat tipe data uuid
            $table->uuid('postingan_id');
            // jadikan postingan_id sebagai primary key
            $table->primary('postingan_id');
            // foreign key atau kunci asing, relasinya adalah 1 postingan ditulis 1 user
            // buat foreign key column di table postingan yaitu user_id yang berelasi dengean column user_id milik table user, ketika user di hapus maka postingan nya juga akan terhapus
            $table->foreignUuid('user_id')->constrained('users')
                ->references('user_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // tipe varchar, column judul_postingan harus unique
            $table->string('judul_postingan')->unique();
            $table->string('slug_postingan');
            $table->string('gambar_postingan');
            // tipe text, column konten
            $table->text('konten_postingan');
            $table->dateTime('postingan_dipublikasi_pada');
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

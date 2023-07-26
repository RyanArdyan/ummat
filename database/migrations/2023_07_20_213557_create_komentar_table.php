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
        // skema buat table komentar, jalankan fungsi, cetakBiru $meja
        Schema::create('komentar', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('komentar_id');
            // foreign key atau kunci asing, relasinya adalah 1 postingan milik atau ditulis oleh 1 user dan 1 user boleh menulis atau memiliki banyak komentar
            // foreign artinya asing, constrained artinya dibatasi
            // buat tipe UNSIGNED BIGINT berarti foreign key, column user_id yang berelasi dengan table user
            $table->foreignId('user_id')->constrained('users')
                // referensi column user_id
                ->references('user_id')
                // ketika diperbarui mengalir
                ->onUpdate('cascade')
                // ketika di hapus mengalir berarti jika user dihapus maka semua komentar nya juga ikut terhapus
                ->onDelete('cascade');
            // tipe unsigned bigint berarti foreign key, column postingan_id yang berelasi dengan table postingan
            $table->foreignId('postingan_id')->constrained('postingan')
            // referensi nya adalah column postingan_id di table postingan
                ->references('postingan_id')
                // jika aku perbarui postingan maka komentar nya juga akan terbarui
                ->onUpdate('cascade')
                // jika aku hapus postingan maka komentar nya juga akan terhapus
                ->onDelete('cascade');
            // parent_id nya boleh kosong berarti jika dia adalah komentar parent maka column parent_id nya kosong, jika dia merupakan child comment atau dibawah nya maka akan ada isi nya misalnya 1 berarti dia membalas komentar_id yg value nya 1
            $table->bigInteger('parent_id')->unsigned()->nullable();
            // tipe text, column komentarnya
            $table->text('komentarnya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};

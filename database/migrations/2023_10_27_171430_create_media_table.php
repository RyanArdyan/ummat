<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FILE INI ADA KARENA AKU MENGGUNAKAN LARAVEL SPATIE untuk menyimpan gambar yg diupload menggunakan CKEditor
    // public fungsi naik
    public function up(): void
    {
        // skema buat table meida jalankan fungsi, cetakBiru, $meja
        Schema::create('media', function (Blueprint $table) {
            // buat tipe data BIG INTEGER, AUTO INCREMENT
            $table->id();
            $table->morphs('model');
            // tipe uuid, column uuid, boleh kosong dan harus unik / tidak boleh sama
            $table->uuid('uuid')->nullable()->unique();
            // tipe string, column nama_koleksi
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            // tipe string, tipe meme, boleh kosong
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            // tipe bigInteger yang tidak ditanda tangani, column ukuran
            $table->unsignedBigInteger('size');
            // tipe json, column manipulasi
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            // tipe unsignedInteger, column kolom_pesanan, boleh kosong dan index
            $table->unsignedInteger('order_column')->nullable()->index();
            // bolehKosongTimestamps
            $table->nullableTimestamps();
        });
    }
};

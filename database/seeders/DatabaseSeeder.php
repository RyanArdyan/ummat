<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Donasi;
use App\Models\Doa;
use App\Models\Kategori;
use App\Models\KegiatanRutin;
use App\Models\KegiatanSekali;
use App\Models\Penceramah;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Benih database aplikasi
     */
    // publik fungsi jalankan: ruang kosong
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // buat data di table user
        // user::pabrik()->buat([])
        User::factory()->create([
            // 1 adalah true berarti dia adalah admin, kalau 0 berarti false berarti dia adalah jamaah
            // column is_admin diisi "1"
            'is_admin' => "1",
            // column foto diisi ''
            'foto' => null,
            // column name diisi value 'Admin'
            'name' => 'Admin',
            // column email diisi value 'admin123@gmail.com'
            'email' => 'admin123@gmail.com',
            // column nik diisi string yang panjang nya 17
            'nik' => '61710211040030010',
            // column nomor_wa diisi string berikut maksimal 15
            'nomor_wa' => '6288705968716',
            // column tgl_lahir berisi string berikut
            'tgl_lahir' => '2003-04-11',
            // column jenis_kelamin diisi 'laki-laki'
            'jenis_kelamin' => 'laki-laki',
            // column password diisi 'pontianak1104' yang sudah di hash
            // berisi hash::buat('pontianak1104')
            'password' => Hash::make('pontianak1104')
        ]);

        // buat data di table user
        // user::pabrik()->buat([])
        User::factory()->create([
            // 1 adalah true berarti dia adalah admin, kalau 0 berarti false berarti dia adalah jamaah
            // column is_admin diisi "0"
            'is_admin' => "0",
            // column foto diisi ''
            'foto' => null,
            // column name diisi value 'Jamaah 1'
            'name' => 'Jamaah 1',
            // column email diisi value 'jamaah1@gmail.com'
            'email' => 'jamaah1@gmail.com',
            // column nik diisi string yang panjang nya 17
            'nik' => '61710211040030019',
            // column nomor_wa diisi string berikut maksimal 15
            'nomor_wa' => '6288705968715',
            // column tgl_lahir berisi string berikut
            'tgl_lahir' => '2002-11-30',
            // column jenis_kelamin diisi 'laki-laki'
            'jenis_kelamin' => 'laki-laki',
            // column password diisi 'pontianak1104' yang sudah di hash
            // berisi hash::buat('pontianak1104')
            'password' => Hash::make('pontianak1104')
        ]);

        // buat data di table donasi
        // donasi::pabrik()->buat([])
        Donasi::create([
            'user_id' => 1,
            'jumlah_donasi' => 1000000,
            'pesan_donasi' => 'Kelola dengan baik',
            'status' => 'Sudah Bayar'
        ]);

        Donasi::create([
            'user_id' => 1,
            'jumlah_donasi' => 1000000,
            'pesan_donasi' => 'Nice',
            'status' => 'Sudah Bayar'
        ]);

        Donasi::create([
            'user_id' => 1,
            'jumlah_donasi' => 1000000,
            'pesan_donasi' => 'Oke',
            'status' => 'Sudah Bayar'
        ]);

        // buat variable dulu agar menyimpan nama_kategori lalu bisa dijadikan slug
        $nama_kategori_1 = "Kategori 1";
        $nama_kategori_2 = "Kategori 2";

        Kategori::create([
            // column nama_kategori berisi value variable $nama_kategori_1
            "nama_kategori" => $nama_kategori_1,
            // berisi buat slug dari variable $nama_kategori_1
            // berisi string, siput()
            "slug_kategori" => Str::slug($nama_kategori_1, '-')
        ]);

        Kategori::create([
            // column nama_kategori berisi value variable $nama_kategori_2
            "nama_kategori" => $nama_kategori_2,
            // berisi buat slug dari variable $nama_kategori_2
            // berisi string, siput()
            "slug_kategori" => Str::slug($nama_kategori_2, '-')
        ]);

        KegiatanRutin::create([
            'nama_kegiatan' => 'Kegiatan Rutin 1',
            'gambar_kegiatan' => 'default_gambar.png',
            'hari' => 'Senin',
            'jam_mulai' => '19:00:00',
            'jam_selesai' => '20:00:00'
        ]);


        KegiatanSekali::create([
            'nama_kegiatan' => 'Kegiatan Sekali 1',
            'gambar_kegiatan' => 'default_gambar.png',
            'tanggal' => '2023-08-15',
            'jam_mulai' => '18:00:00',
            'jam_selesai' => '20:00:00'
        ]);

        KegiatanSekali::create([
            'nama_kegiatan' => 'Kegiatan Sekali 2',
            'gambar_kegiatan' => 'default_gambar.png',
            'tanggal' => '2023-08-20',
            'jam_mulai' => '15:00:00',
            'jam_selesai' => '17:00:00'
        ]);

        Penceramah::create([
            'nama_penceramah' => "Habib Ja'far",
            'foto_penceramah' => 'default_gambar.png'
        ]);

        Penceramah::create([
            'nama_penceramah' => "Ustadz Abdul Somad",
            'foto_penceramah' => 'default_gambar.png'
        ]);


    }
}

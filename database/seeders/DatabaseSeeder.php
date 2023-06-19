<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
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
            // column foto diisi 'default_foto.jpg'
            'foto' => 'foto_default.png',
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
            // column foto diisi 'default_foto.jpg'
            'foto' => 'foto_default.png',
            // column name diisi value 'Jamaah 1'
            'name' => 'Jamaah 1',
            // column email diisi value 'jamaah1@gmail.com'
            'email' => 'jamaah1@gmail.com',
            // column tgl_lahir berisi string berikut
            'tgl_lahir' => '2002-11-30',
            // column jenis_kelamin diisi 'laki-laki'
            'jenis_kelamin' => 'laki-laki',
            // column password diisi 'pontianak1104' yang sudah di hash
            // berisi hash::buat('pontianak1104')
            'password' => Hash::make('pontianak1104')
        ]);
    }
}

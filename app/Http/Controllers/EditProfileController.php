<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
// aku kustom atau membuat validasi formulir sendiri
use App\Rules\ValidasiNomorWhatsapp;
// package image intervention untuk kompress gambar, ubah lebar dan tinggi gambar dan lain-lain.
// image adalah alias yang di daftarkan di config/app
use Image;

class EditProfileController extends Controller
{
    // halaman detail user
    public function edit()
    {
        // berisi detail user yang login
        $detail_user_yang_login =  auth()->user();
        // kembalikan ke view edit_profile.index, sambil kirimkan detail_user_yang_login
        return view('edit_profile.index', ['detail_user_yang_login' => $detail_user_yang_login]);
    }

    // logic perbarui detail user
    // $request akan menangkap semua value input lewat new FormData(this) yang dikirim script
    public function update(Request $request)
    {
        // ambil detail user yang login
        // autentikasi()->pengguna();
        $detail_user_yang_login = auth()->user();
        $input_name = $request->name;
        $input_nik = $request->nik;

        // jika nilai input name sama dengan value detail_user_yg_login, column name berarti user tidak mengubah nama nya
        if ($input_name === $detail_user_yang_login->name) {
            // name harus wajib, string, minimal 3 dan maksimal 50
            $validasi_name = ['required', 'string', 'min:3', 'max:50'];
        }
        // lain jika input name tidak sama dengan detail_user column name berarti user mengubah nama nya
        else if ($input_name !== $detail_user_yang_login->name) {
            // validasi name wajib, string, min 3, max 50  dan harus unik dari detail users
            $validasi_name = ['required', 'string', 'min:3', 'max:50', 'unique:users'];
        };

        // jika nilai input nik sama dengan value detail_user_yg_login, column nik berarti user tidak mengubah nama nya
        if ($input_nik === $detail_user_yang_login->nik) {
            // nik harus wajib, minimal 16 dan maksimal 18
            $validasi_nik = ['required', 'min:16', 'max:18'];
        }
        // lain jika input nik tidak sama dengan detail_user column nik berarti user mengubah nama nya
        else if ($input_nik !== $detail_user_yang_login->nik) {
            // nik harus wajib, minimal 16 dan maksimal 18 dan harus unique
            $validasi_nik = ['required', 'min:16', 'max:18', 'unique:users'];
        };

        // hapus spasi pada value input name="nomor_wa" misalnya berisi "62 887" maka akan menjadi "62887"
        // berisi str_ganti('', '', $permintaan->nomor_wa)
        $value_input_nomor_wa = str_replace(' ', '', $request->nomor_wa);

        // jika nilai variable $value_input_nomor_wa sama dengan nilai $detail_user_yang_login, column nomor_wa berarti user tidak mengubah nomor_wa nya
        if ($value_input_nomor_wa === $detail_user_yang_login->nomor_wa) {
            // value input nomor_wa itu wajib dan harus dimulai dari 62, aku menambahkan validasi sendiri atau custom validasi menggunakan App/Rules/ValidasiNomorWhatsapp
           $validasi_nomor_wa = ['required', new ValidasiNomorWhatsapp];
        }
        // lain jika value variable $value_input_nomor_wa tidak sama dengan value detail_user, column nomor_wa berarti user mengubah nomor_wa nya
        else if ($value_input_nomor_wa !== $detail_user_yang_login->nomor_wa) {
            // value input nomor_wa itu wajib dan harus dimulai dari 62, aku menambahkan validasi sendiri atau custom validasi menggunakan App/Rules/ValidasiNomorWhatsapp, value nya harus unik atau tidak boleh sama
           $validasi_nomor_wa = ['required', 'unique:users', new ValidasiNomorWhatsapp];
        };

        // jadi aku mengatur apa-apa saja yang akan aku validasi, dan memodifikasi nya agar tidak hanya value attribute name yang aku validasi tapi value variable $value_input_nomor_wa juga aku validasi
        // berisi ara
        $input = [
            
            'name' => $input_name,
            'nik' => $request->nik,
            'nomor_wa' => $value_input_nomor_wa,
            'tgl_lahir' => $request->tgl_lahir,
            'foto' => $request->foto
        ];

        // jika user memiliki file foto atau jika user mengganti foto
        // jika ($permintaan->memilikiFile('foto'))
        if ($request->hasFile('foto')) {
            // harus berupa gambar
            $validasi_foto = 'image';
        } 
        // lain jika user tidak mengupload foto
        // lain jika $permintaan tidka memiliki file foto
        else if (!$request->hasFile('foto')) {
            // berisi tanpa validasi
            $validasi_foto = '';
        };
        // tanpa validasi

        // validasi semua input yg aku modifikasi lewat variable $input_ya
        // berisi validator::buat validasi dari value variable $input_nama
        $validator = Validator::make($input, [
            // input attribute name yang berisi name harus menggunakan aturan dari $validasi_name
            'name' => $validasi_name,
            // value nik mengikut value dari variable $validasi_nik
            'nik' => $validasi_nik,
            // value input name="nomor_wa" harus mengikuti aturan dari variable $vl
            'nomor_wa' => $validasi_nomor_wa,
            'tgl_lahir' => 'required',
            // input foto harus berisi gambar
            'foto' => $validasi_foto
        ],
        // Terjamahan validasi 
        [
            // terjemahan untuk validasi name.unique
            'name.unique' => 'Orang lain sudah menggunakan nama itu.',
            'nik.unique' => 'Orang lain sudah menggunakan nik itu.',
            'nomor_wa' => 'Orang lain sudah menggunakan nomor wa itu.'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key kesalahan2x berisi semua value attribute name dan semua pesan errornya 
                // key kesalahan2x berisi $validator->kesalahan2x()->keArray()
                'errors' => $validator->errors()->toArray()
            ]);
        } 
        // jika validasi berhasil
        else {
            // jika value input name="nomor_wa" yang dimasukkan tidak dimulai dari 62 maka
            // if ()
            // kembalikkan tanggapan berupa json
            // key pesan "Value input nomor whatsapp harus dimulai dari 62"


            // jika user memiliki file foto atau jika user mengganti foto
            // jika ($permintaan->memilikiFile('foto'))
            if ($request->hasFile('foto')) {
                // jika value detail user yang login, column foto nya sama dengan default_foto.jpg maka
                if ($detail_user_yang_login->foto === 'default_foto.jpg') {
                    // lakukan upload gambar
                    // $nama_foto_baru misalnya berisi 12345.jpg
                    // waktu() . '.' . $permintaan->file('foto')->ekstensi();
                    $nama_foto_baru = time() . '.' . $request->file('foto')->extension();
                    // upload gambar dan ganti nama gambar
                    // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                    // argumen kedua adalah value input name="foto"
                    // argument ketiga adalah nama file gambar baru nya
                    Storage::putFileAs('public/foto_profil/', $request->file('foto'), $nama_foto_baru);

                    // berisi panggil gambar dan jalur nya
                    $jalur_gambar = public_path("storage/foto_profil/$nama_foto_baru");

                    // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                    // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 600, dan tinggi nya 600, resize gambar juga termasuk kompres gamabr
                    $gambar = Image::make($jalur_gambar)->resize(600, 600);

                    // argument pertama pada save adalah simpan gambar dengan cara timpa file
                    // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
                    // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                    $gambar->save($jalur_gambar, 100, 'jpg');
                // lain jika value pada detail)user_yang_login, column foto tidak sma dengan 'default_foto.jpg' maka itu berarti aku akan menghapus file foto nya
                } else if ($detail_user_yang_login->foto !== 'default_foto.jpg') {
                    // hapus foto lama
                    // Penyimpanan::hapus('/public/foto_profil/' digabung value detail_user_yang_login, column foto
                    Storage::delete('public/foto_profil/' . $detail_user_yang_login->foto);

                    // lakukan upload gambar
                    // $nama_foto_baru misalnya berisi 12345.jpg
                    // waktu() . '.' . $permintaan->file('foto')->ekstensi();
                    $nama_foto_baru = time() . '.' . $request->file('foto')->extension();
                    // upload gambar dan ganti nama gambar
                    // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                    // argumen kedua adalah value input name="foto"
                    // argument ketiga adalah nama file gambar baru nya
                    Storage::putFileAs('public/foto_profil/', $request->file('foto'), $nama_foto_baru);

                    // berisi panggil gambar dan jalur nya
                    $jalur_gambar = public_path("storage/foto_profil/$nama_foto_baru");

                    // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                    // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 600, dan tinggi nya 600, resize gambar juga termasuk kompres gamabr
                    $gambar = Image::make($jalur_gambar)->resize(600, 600);

                    // argument pertama pada save adalah simpan gambar dengan cara timpa file
                    // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
                    // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                    $gambar->save($jalur_gambar, 100, 'jpg');
                };
            } 
            // jika user tidak mengupload foto lewat input name="foto" maka pakai value column detail_user_yang_login, column foto ketia update profile
            // lain jika $permintaan tidak memiliki file dari input name="foto"
            else if (!$request->hasFile('foto')) {
                // berisi memanggil value detail user, column foto
                $nama_foto_baru = $detail_user_yang_login->foto;
            };

            // perbarui user
            // panggil value detail_user column name di table lalu isi dengan value input name
            $detail_user_yang_login->name = $input_name;
            // panggil value detail_user column nik di table lalu isi dengan value input nik
            $detail_user_yang_login->nik = $request->nik;
            // panggil value detail_user column nomor_wa di table lalu isi dengan value variable $value_input_nomor_wa
            $detail_user_yang_login->nomor_wa = $value_input_nomor_wa;
            // panggil value detail_user column tgl_lahir di table lalu isi dengan value input tgl_lahir
            $detail_user_yang_login->tgl_lahir = $request->tgl_lahir;
            // panggil value detail_user column jenis_kelamin di table lalu isi dengan value input jenis_kelamin
            $detail_user_yang_login->jenis_kelamin = $request->jenis_kelamin;
            // panggil value detail_user column foto di table lalu isi dengan value input foto
            $detail_user_yang_login->foto = $nama_foto_baru;
            // detail_user_yang_login, perbarui perubahan
            $detail_user_yang_login->update();

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi "Profile berhasil diperbaru"
                'pesan' => 'Profile berhasil diperbarui.',
                // aku mengirimkan detail_user, column name agar aku bisa mengupdate nama di layouts/top-navbar
                'detail_name' => $detail_user_yang_login->name,
                // aku mengirimkan detail_user, column foto agar aku bisa mengupdate foto profile di layouts/top-navbar
                'detail_foto' => $detail_user_yang_login->foto,
            ]);
        };
    }

    // Update Password
    // $request akan menangkap semua value attribute name
    public function update_password(Request $request) 
    {
        // ambil detail user berdasarkan value user yang login
        // berisi autenetikasi()->pengguna();
        $detail_user = auth()->user();

        // validasi
        // berisi validator::buat($permintaan->semua())
        $validator = Validator::make($request->all(), [
            // value input name password_lama harus wajib, minimal 6, maksimal 20
            'password_lama' => ['required', 'min:6', 'max:20'],
            // value input name password_baru harus wajib, minimal 6, maksimal 20
            'password_baru' => ['required', 'min:6', 'max:20']
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan response berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        }
        // jika validasi berhasil
        else { 
            // jika input password lama sama dengan detail user column password maka
            if (Hash::check($request->password_lama, $detail_user->password)) {
                // jika value input password baru sama dengan password lama maka tidak boleh
                if (Hash::check($request->password_baru, $detail_user->password)) {
                    // kembalikan tangapan berupa json
                    return response()->json([
                        // key pesan berisi value 
                        'pesan' => 'Password baru tidak boleh sama dengan password lama'
                    ]);
                } 
                // jika value input name="password_baru" tidak sama dengan value detail_user_yg_login, column password berarti password lama, maka update password berdasarkan password baru
                else if (!Hash::check($request->password_baru, $detail_user->password)) {
                    // Perbarui password
                    // panggil detail_user, column password diisi dengan value input password_baru yang di hash
                    $detail_user->password = Hash::make($request->password_baru);
                    // detail_user di perbarui
                    $detail_user->update();
                    // kembalikkan tanggapan berupa json
                    return response()->json([
                        // key status berisi value 200
                        'status' => 200,
                        // key pesan berisi string berikut
                        'pesan' => "Password berhasil diperbarui.",
                    ]);
                }
            // jika value input password lama tidak sama dengan null berarti ada isinya namun value input tidak sama dengan detail_user, column password berarti password lama salah
            } else {
                // kembalikan response berupa json 'Password lama salah'
                return response()->json([
                    // key pesan berisi value "Password lama salah"
                    'pesan' => 'Password lama salah'
                ]);
            };
        };
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        // jika nilai input name sama dengan nilai column name dari $detail_user_yang_login berarti user tidak mengubah nama nya
        if ($request->name === $detail_user_yang_login->name) {
            // name harus wajib, string, minimal 3 dan maksimal 50
            $validasi_name = 'required|string|min:3|max:50';
        }
        // lain jika input name tidak sama dengan detail_user column name berarti user mengubah nama nya
        else if ($request->name !== $detail_user_yang_login->name) {
            // validasi name wajib, string, min 3, max 50  dan harus unik dari detail users
            $validasi_name = 'required|string|min:3|max:50|unique:users';
        };

        // validasi semua input 
        // berisi validator::buat($permintaan->semua)
        $validator = Validator::make($request->all(), [
            // input attribute name yang berisi name harus menggunakan aturan dari $validasi_name
            'name' => $validasi_name,
            // value nik harus wajib, panjang minimal nik adalah 16, maksimal nik adalah 18, 
            'nik' => 'required|min:16|max:18',
            // value input nomor_wa itu wajib
            'nomor_wa' => 'required',
            'tgl_lahir' => 'required',
            // input foto harus berisi gambar dan maksimal ukuran gambar nya adalah 600
            'foto' => 'image|max:600'
        ],
        // Terjamahan validasi 
        [
            // terjemahan untuk validasi name.unique
            'name.unique' => 'Orang lain sudah menggunakan nama itu.',
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
            // jika user memiliki file foto atau jika user mengganti foto
            // jika ($permintaan->memilikiFile('foto'))
            if ($request->hasFile('foto')) {
                // jika value detail user yang login, column foto nya sama dengan 'foto_default.png maka
                if ($detail_user_yang_login->foto === 'foto_default.png') {
                    // jangan hapus file foto_default.png
                    // lakukan upload foto
                    // nama foto baru
                    // anggaplah berisi 123_1.jpg
                    $nama_foto_baru = time() . '_' . $request->id . '.' . $request->file('foto')->extension();
                    // upload foto dan ganti nama foto
                    // argument pertama pada putFileAs adalah tempat atau folder foto akan disimpan
                    // argumen kedua adalah input name="foto"
                    // argument ketiga adalah nama file foto nya
                    Storage::putFileAs('public/foto_profil/', $request->file('foto'), $nama_foto_baru);
                // lain jika value pada detail)user_yang_login, column foto tidak sma dengan 'foto_default.png' maka itu berarti aku akan menghapus file foto nya
                } else if ($detail_user_yang_login->foto !== 'foto_default.png') {
                    // hapus foto lama
                    // Penyimpanan::hapus('/public/foto_profil/' digabung value detail_user_yang_login, column foto
                    Storage::delete('public/foto_profil/' . $detail_user_yang_login->foto);
                    // nama foto baru
                    // anggaplah berisi 123_1.jpg
                    $nama_foto_baru = time() . '_' . $request->id . '.' . $request->file('foto')->extension();
                    // upload foto dan ganti nama foto
                    // argument pertama pada putFileAs adalah tempat atau folder foto akan disimpan
                    // argumen kedua adalah input name="foto"
                    // argument ketiga adalah nama file foto nya
                    Storage::putFileAs('public/foto_profil/', $request->file('foto'), $nama_foto_baru);
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
            $detail_user_yang_login->name = $request->name;
            // panggil value detail_user column nik di table lalu isi dengan value input nik
            $detail_user_yang_login->nik = $request->nik;
            // panggil value detail_user column nomor_wa di table lalu isi dengan value input nomor_wa
            $detail_user_yang_login->nomor_wa = $request->nomor_wa;
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

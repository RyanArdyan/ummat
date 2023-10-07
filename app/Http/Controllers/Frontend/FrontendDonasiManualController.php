<?php

namespace App\Http\Controllers\Frontend;

// import atau gunakan 
// memperluas kelas dasar
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// aku kustom atau membuat validasi formulir sendiri
use App\Rules\ValidasiNomorWhatsapp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\DonasiManual;

class FrontendDonasiManualController extends Controller
{
    // menampilkan halaman formulir donasi
    public function create(Request $request)
    {
        // ambil value detail_user yg login, column nomor_wa
        $nomor_wa_user = $request->user()->nomor_wa;

        // kembalikkan ke tampilan frontend.donasi_manual.formulir_create, lalu kirimkan data berupa array
        return view('frontend.donasi_manual.formulir_create', [
            // kunci nomor_wa_user berisi value variable $nomor_wa_user
            'nomor_wa_user' => $nomor_wa_user
        ]);
    }

    // parameter $permintaan berisi semua value attribute name milik input
    public function store(Request $request)
    {
        // ambil value detail_user yang login
        // autentikasi()->pengguna();
        $detail_user_yang_login = auth()->user();

        // berisi menangkap value input name="nomor_wa"
        // berisi $permintaan->nomor_wa
        $value_input_nomor_wa = $request->nomor_wa;

        // jika nilai variable $value_input_nomor_wa sama dengan nilai $detail_user_yang_login, column nomor_wa berarti user tidak mengubah nomor_wa nya berarti tidak perlu unique
        if ($value_input_nomor_wa === $detail_user_yang_login->nomor_wa) {
            // value input nomor_wa itu wajib dan harus dimulai dari 62, aku menambahkan validasi sendiri atau custom validasi menggunakan App/Rules/ValidasiNomorWhatsapp
            $validasi_nomor_wa = ['required', new ValidasiNomorWhatsapp];
        }
        // lain jika value variable $value_input_nomor_wa tidak sama dengan value detail_user, column nomor_wa berarti user mengubah nomor_wa nya berarti harus unique
        else if ($value_input_nomor_wa !== $detail_user_yang_login->nomor_wa) {
            // value input nomor_wa itu wajib,  value nya harus unik atau tidak boleh sama dan harus dimulai dari 62, aku menambahkan validasi sendiri atau custom validasi menggunakan App/Rules/ValidasiNomorWhatsapp
            $validasi_nomor_wa = ['required', 'unique:users', new ValidasiNomorWhatsapp];
        };

        // validasi value input2x
        // berisi $permintaan->validasi
        $validasi = $request->validate([
            // value input name jumlah_donasi harus wajib, integer dan maksimal nya adalah 2 milyar
            'jumlah_donasi' => ['required', 'integer', 'max:2000000000'],
            // value input name="nomor_wa" harus mengikuti aturan dari variable $validasi_nomor_wa
            'nomor_wa' => $validasi_nomor_wa,
            // harus diisi, harus berupa gambar, dan maksimal adalah 500 kb
            'foto_bukti' => ['required', 'image', 'max:500'],
            // value input name pesan_donasi harus wajib
            'pesan_donasi' => 'required',
        ],
        // Terjamahan validasi 
        [
            // terjemahan untuk validasi nomor_wa.unique
            'nomor_wa.unique' => 'Orang lain sudah menggunakan nomor wa itu.'
        ]);

        // berisi ambil value detail_user yg login, column user_id
        // berisi autentikasi::pengguna()->pengguna_id
        $user_id = Auth::user()->user_id;
        // berisi ambil value input name="jumlah_donasi"
        // berisi $permintaan->jumlah_donasi
        $jumlah_donasi = $request->jumlah_donasi;
        $pesan_donasi = $request->pesan_donasi;
        // berisi ambil value dari autentikasi::pengguna()->email
        $email_user = Auth::user()->email;
        // berisi ambil value dari autentikasi::pengguna()->nomor_wa
        $nomor_wa_user = Auth::user()->nomor_wa;
        $nama_user = Auth::user()->name;
        $tipe_pembayaran = $request->tipe_pembayaran;

        // lakukan upload gambar
        // $nama_gambar_baru misalnya berisi tokomu_3242312345.jpg
        // $permintaan->file('gambar_kegiatan')->hashNama();
        $nama_gambar_baru = "tokomu_" . $request->file('foto_bukti')->hashName();

        // kembalikkan tanggapan berupa json dari variable $nama_gambar_baru
        return response()->json($nama_gambar_baru);



        // // upload gambar dan ganti nama gambar
        // // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
        // // argumen kedua adalah value input name="foto_bukti"
        // // argument ketiga adalah nama file gambar baru nya
        // Storage::putFileAs('public/donasi_manual/', $request->file('foto_bukti'), $nama_gambar_baru);

        // // Simpan donasi ke table donasi_manual
        // // donasi_manual, buat
        // $detail_donasi = DonasiManual::create([
        //     // column user_id di table donasi diisi dengan value variable user_id
        //     'user_id' => $user_id,
        //     // column jumlah_donasi di table donasi diisi dengan value variable $jumlah_donasi
        //     'jumlah_donasi' => $jumlah_donasi,
        //     // column pesan_donasi di table donasi diisi dengan value variable $pesan_donasi
        //     'pesan_donasi' => $pesan_donasi
        // ]);


        // // kembalikkan lalu alihkan ke route donasi.detail lalu kirimkan data berupa array
        // return redirect()->route('donasi.detail', [
        //     // key donasi_id berisi value detail_donasi, column donasi_id
        //     'donasi' => $detail_donasi->donasi_id
        // ])
        // // Mengarahkan Ulang Dengan Data Sesi yang Di-Flash
        // // dengan variable status berisi value berikut
        // ->with('status', 'Silahkan lakukan pembayaran donasi.');
    }
}

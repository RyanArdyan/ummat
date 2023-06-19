<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// gunakan atau import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
// package image intervention untuk kompress gambar, ubah lebar dan tinggi gambar dan lain-lain.
// image adalah alias yang di daftarkan di config/app
use Image;
// package laravel datatables
use DataTables;
use App\models\Kegiatan;

class KegiatanRutinController extends Controller
{
    // Method index menampilkan halaman kegiatan rutin
    // publik fungsi index
    public function index()
    {
        // berisi ambil value detail user yang login, column is_admin
        $is_admin = Auth::user()->is_admin;
        // jika yang login adalah admin maka 
        // jika value variable is_admin nya sama dengan "1"
        if ($is_admin === "1") {
            // kembalikkan ke admin.kegiatan_rutin.index
            return view('admin.kegiatan_rutin.index');
        }
        // lain jika yang login adalah jamaah maka
        else if ($is_admin === "0") {
            // kembalikkan ke jamaah.kegiatan_rutin.index
            return view('jamaah.kegiatan_rutin.index');
        };
    }

    // menampilkan semua data table kegiatan, yang column tipe_kegiatan nya berisi "Kegiatan Rutin".
    public function read()
    {
        // ambil semua value dari column kegiatan_id, nama_kegiatan dan lain-lain dimana value column tipe_kegiatan sama dengan "Kegiatan Rutin", dapatkan semua data nya
        // beriisi Kegiatan::pilih('kegiatan_id', 'nama_kegiatan', 'dan-lain-lain') dimana value column tipe_kegiatan sama dengan value 'Kegiatan Rutin', dapatkan()
        $semua_kegiatan = Kegiatan::select('kegiatan_id', 'nama_kegiatan', 'gambar_kegiatan', 'tanggal', 'jam_mulai', 'jam_selesai')->where('tipe_kegiatan', 'Kegiatan Rutin')->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_kegiatan
        return DataTables::of($semua_kegiatan)
            // nomor kegiatan
            // tambah index column
            ->addIndexColumn()
            // ulang detail_kegiatan menggunakan $kegiatan
            // tambah column pilih, jalankan fungsi, kegiatan $kegiatan
            ->addColumn('select', function(kegiatan $kegiatan) {
                // return element html
                // name="kegiatan_ids[]" karena name akan menyimpan array yang berisi beberapa kegiatan_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column kegiatan_id
                return '
                        <input name="kegiatan_ids[]" value="' . $kegiatan->kegiatan_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('gambar_kegiatan', function(Kegiatan $kegiatan) {
                // buat img, yg attribute src nya memanggil public/storage/gambar_kegiatan/$kegiatan->gambar_kegiatan, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img src='/storage/gambar_kegiatan/$kegiatan->gambar_kegiatan' width='50px' height='50px'>";

            })
            ->addColumn('tanggal', function(kegiatan $kegiatan) {
                // isoFormat adalah package dari carbon, laravel secara bawaan, menginstal nya
                // isoFormat() akan membuat tanggal mudah dibaca, dddd adalah nama hari, D adalah tanggal, MMMM adalah bulan, Y adalah tahun.
                return $kegiatan->tanggal;
            })
            // buat tombol edit
            // tambahKolom('action', fungsi(Kegiatan $kegiatan))
            ->addColumn('action', function(kegiatan $kegiatan) {
                // jika ingin membuat sebuah attrbute sendiri bisa pakae data-nama-attribute
                // attribute data-id akan mencetak setiap value kegiatan_id
                return  '
                    <button data-id="' . $kegiatan->kegiatan_id . '" class="tombol_edit btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                ';
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat elemnt html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'gambar_kegiatan', 'tanggal', 'action'])
        // buat benar
        ->make(true);
    }

    // method buat untuk menampilkan formulir tambah kegiatan
    // publik fungsi buat()
    public function create()
    {
        // kembalikkan ke tampilan admin.kegiatan_rutin.formulir_create
        return view('admin.kegiatan_rutin.formulir_create');
    }

    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator dibuat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_kegiatan harus wajib dan maksimal nya adalah 255
            'nama_kegiatan' => 'required|max:255',
            // value input name tanggal harus wajib
            'tanggal' => 'required',
            // value input name jam_mulai harus wajib
            'jam_mulai' => 'required',
            // value input name jam_selesai harus wajib
            'jam_selesai' => 'required',
            // value input name gambar_kegiatan harus wajib, harus berupa gambar.
            'gambar_kegiatan' => 'required|image',
        ]);

        // buat validasi
        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key pesan berisi pesan berikut
                'pesan' => 'Validasi Menemukan Error',
                // key errors berisi semua value input dan pesan yang error
                'errors' => $validator->errors()
            ]);
        }
        // jika validasi berhasil
        else {
            // lakukan upload gambar
            // $nama_gambar_baru misalnya berisi 12345.jpg
            // waktu() . '.' . $permintaan->file('gambar_kegiatan')->ekstensi();
            $nama_gambar_baru = time() . '.' . $request->file('gambar_kegiatan')->extension();
            // upload gambar dan ganti nama gambar
            // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
            // argumen kedua adalah value input name="gambar_kegiatan"
            // argument ketiga adalah nama file gambar baru nya
            $file_gambar = Storage::putFileAs('public/gambar_kegiatan/', $request->file('gambar_kegiatan'), $nama_gambar_baru);

            // berisi panggil gambar dan jalur nya
            $jalur_gambar = public_path("storage/gambar_kegiatan/$nama_gambar_baru");

            // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
            // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500, dan tinggi nya 300, resize gambar juga termasuk kompres gamabr
            $gambar = Image::make($jalur_gambar)->resize(500, 300);

            // argument pertama pada save adalah simpan gambar dengan cara timpa file
            // argument kedua pada save adalah kualitas nya aku turunkan sedikit menjadi 90% agar terkompress, 
            // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
            $gambar->save("$jalur_gambar", 90, 'jpg');

            // Simpan kegiatan ke table kegiatan
            // kegiatan buat
            kegiatan::create([
                // column nama_kegiatan di table kegiatan diisi dengan value input name="nama_kegiatan"
                'nama_kegiatan' => $request->nama_kegiatan,
                'gambar_kegiatan' => $nama_gambar_baru,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'tipe_kegiatan' => $request->tipe_kegiatan,
            ]);
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "kegiatan PT Bisa berhasil disimpan."
                'pesan' => "kegiatan $request->nama_kegiatan berhasil disimpan.",
            ]);
        };
    }
}

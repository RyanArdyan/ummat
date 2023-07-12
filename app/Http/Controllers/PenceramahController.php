<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// gunakan atau import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// package image intervention untuk kompress gambar, ubah lebar dan tinggi gambar dan lain-lain.
// image adalah alias yang di daftarkan di config/app
use Image;
// package laravel datatables
use DataTables;
use App\Models\Penceramah;

class PenceramahController extends Controller
{
    // Method index menampilkan halaman penceramah
    // publik fungsi index
    public function index()
    {
        // berisi ambil value detail user yang autetikasi atau login, column is_admin
        $is_admin = Auth::user()->is_admin;

        // jika yang login adalah admin maka 
        // jika value variable is_admin nya sama dengan "1"
        if ($is_admin === "1") {
            // // kembalikkan ke tampilan admin.penceramah.index, kirimkan data berupa array
            return view('admin.penceramah.index');

            // return response()->json($semua_penceramah);
        }
        // lain jika yang login adalah jamaah maka
        else if ($is_admin === "0") {
            // ambil semua penceramah, ambil data terbaru
            // berisi penceramah, pilih column user_id agar relasi nya terpanggil nama_penceramah, slug_penceramah, foto_penceramah, dipublikasi_pada di pesan oleh value column updated_at, data yang paling baru, dapatkan semua data nya
            $semua_penceramah = Penceramah::select("nama_penceramah", 'foto_penceramah', )->orderBy('updated_at', 'desc')->get();

            // kembalikkan tanggapna berupa json lalu kirimkan value $semua_penceramah
            // return response()->json($semua_penceramah);

            // kembalikkan ke tampilan jamaah.penceramah.index, kirimkan data berupa array, 
            return view('jamaah.penceramah.index', [
                // key semua_penceramah berisi value $semua_penceramah
                'semua_penceramah' => $semua_penceramah
            ]);
        };
    }

    // menampilkan semua data table penceramah
    public function read()
    {
        // jadi ambil semua_penceramah lalu data terbaru akan tampil yang pertama
        // berisi penceramah pilih semua value column penceramah_id, nama_penceramah, foto_penceramah, dipesanOleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_penceramah = Penceramah::select('penceramah_id', 'nama_penceramah', 'foto_penceramah')->orderBy("updated_at", "desc")->get();

        // syntax punya yajra
        // kembalikkan datatables dari semua_penceramah
        return DataTables::of($semua_penceramah)
            // nomor penceramah
            // tambah index column
            ->addIndexColumn()
            // ulang detail_penceramah menggunakan $penceramah
            // tambah column pilih, jalankan fungsi, penceramah $penceramah
            ->addColumn('select', function(Penceramah $penceramah) {
                // return element html
                // name="penceramah_ids[]" karena name akan menyimpan array yang berisi beberapa penceramah_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column penceramah_id
                return '
                        <input name="penceramah_ids[]" value="' . $penceramah->penceramah_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            // tambah column foto_penceramah, jalankan fungsi, parameter $penceramah berisi setiap value detail_penceramah
            ->addColumn('foto_penceramah', function(Penceramah $penceramah) {
                // buat img, yg attribute src nya memanggil public/storage/foto_penceramah/ value dari $penceramah->foto_penceramah, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img src='/storage/foto_penceramah/$penceramah->foto_penceramah' width='50px' height='50px'>";

            })
            // buat tombol edit
            // tambahKolom('aksi', fungsi(Penceramah $penceramah)) parameter $penceramah berisi setiap value detail_penceramah
            ->addColumn('action', function(Penceramah $penceramah) {
                // panggil url /penceramah/edit/ lalu kirimkan value detail_penceramah, column penceramah_id nya agar aku bisa mengambil detail penceramah berdasarkan penceramah_id, aku akan gunakan fitur pengingakatan route model
                return  "
                    <a href='/penceramah/edit/$penceramah->penceramah_id' class='btn btn-warning btn-sm'>
                        <i class='fas fa-pencil-alt'></i> Edit
                    </a>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'foto_penceramah', 'action'])
        // buat benar
        ->make(true);
    }

    // method buat untuk menampilkan formulir tambah penceramah
    // publik fungsi buat()
    public function create()
    {
        // kembalikkan ke tampilan admin.penceramah.formulir_create
        return view('admin.penceramah.formulir_create');
    }

    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua input yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_penceramah harus wajib, unik dan maksimal nya adalah 255
            'nama_penceramah' => 'required|unique:penceramah|max:255',
            // value input name foto_penceramah harus wajib, harus berupa gambar.
            'foto_penceramah' => 'required|image',
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
                'errors' => $validator->errors(),
            ]);
        }
        // jika validasi berhasil
        else {
            // lakukan upload gambar
            // $nama_gambar_baru misalnya berisi 3423432.jpg
            // waktu() digabung '.' digabung $permintaan->file('foto_penceramah')->ekstensi();
            $nama_gambar_baru = $request->file('foto_penceramah')->hashName();
            // upload gambar dan ganti nama gambar
            // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
            // argumen kedua adalah value input name="foto_penceramah"
            // argument ketiga adalah nama file gambar baru nya
            Storage::putFileAs('public/foto_penceramah/', $request->file('foto_penceramah'), $nama_gambar_baru);

            // berisi panggil gambar dan jalur nya berarti panggil folder public/storage
            $jalur_gambar = public_path("storage/foto_penceramah/$nama_gambar_baru");

            // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
            // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500 , dan tinggi nya 285, resize gambar juga termasuk kompres gambar
            // berisi gambar buat value variable $jalur_gambar, atur ulang ukuran gambar nya menjadi lebar 500 dan tinggi menjadi 285
            $gambar = Image::make($jalur_gambar)->resize(500, 285);

            // argument pertama pada simpan() adalah simpan gambar dengan cara timpa file
            // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
            // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi jpg
            $gambar->save($jalur_gambar, 100, 'jpg');



            // Simpan penceramah ke table penceramah
            // berisi penceramah buat
            $penceramah = Penceramah::create([
                // column nama_penceramah di table penceramah diisi dengan value input name="nama_penceramah"
                'nama_penceramah' => $request->nama_penceramah,
                // column foto_penceramah pada table penceramah diisi value variable $nama_gambar_baru
                'foto_penceramah' => $nama_gambar_baru,
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "penceramah wawan Bisa berhasil disimpan."
                'pesan' => "penceramah $request->nama_penceramah berhasil disimpan.",
            ]);
        };
    }

    // // method show, parameter $penceramah itu fitur Pengikatan Model Rute jadi parameter $penceramah berisi detail_penceramah berdasarkan slug_penceramah yang dikirimkan
    // public function show(Penceramah $penceramah)
    // {
    //     // kembalikkkan ke tampilan jamaah.penceramah.detail_penceramah, lalu kirimkan array yang berisi key detail_penceramah berisi value variable $detail_penceramah
    //     return view('jamaah.penceramah.detail_penceramah', ['detail_penceramah' => $penceramah]);
    // }

    // method edit, parameter $penceramah itu fitur Pengikatan Model Rute jadi parameter $penceramah_id berisi detail_penceramah berdasarkan penceramah_id yang dikirimkan oleh url 
    public function edit(Penceramah $penceramah)
    {
        // kembalikkkan ke tampilan admin.penceramah.formulir_edit, lalu kirimkan array
        return view('admin.penceramah.formulir_edit', [
            // key detail_penceramah berisi value variable $detail_penceramah
            'detail_penceramah' => $penceramah,
        ]);
    }

    // method perbarui untuk memperbarui penceramah 
    // parameter $permintaan berisi semua value input
    // $penceramah berisi mengambil detail_penceramah berdasarkan penceramah_id dari yang dikirim url
    public function update(Request $request, penceramah $penceramah)
    {
        // jika nilai input nama_penceramah sama dengan nilai column nama_penceramah dari detail $penceramah berarti user tidak mengubah nama_penceramahnya nya
        if ($request->nama_penceramah === $penceramah->nama_penceramah) {
            // nama_penceramah harus wajib, dan maksimal 255
            $validasi_nama_penceramah = 'required|max:255';
        }
        // lain jika value input name="nama_penceramah" tidak sama dengan detail_penceramah, column nama_penceramah berarti user mengubah nama_penceramah nya atau maka baris detail_penceramah akan di skip datanya lalu value input nama_penceramah tidak boleh sama dengan baris detail_penceramah, column nama_penceramah lain nya
        else if ($request->nama_penceramah !== $penceramah->nama_penceramah) {
            // validasi nama_penceramah harus wajib, unik, max 255 
            $validasi_nama_penceramah = 'required|unique:penceramah|max:255';
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_penceramah mengikut aturan dari value variabel $validasi_nama_penceramah
            'nama_penceramah' => $validasi_nama_penceramah,
            // value input name foto_penceramah harus berupa gambar.
            'foto_penceramah' => 'image',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkaan tangapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan error nya
                // berisi $validator->kesalahan2x->keArray()
                'errors' => $validator->errors()->toArray()
            ]);
        } 
        // jika validasi berhasil
        else {
            // jika user mengganti atau mengupload foto_penceramah
            // jika ($permintaan->memilikiFile('foto_penceramah'))
            if ($request->hasFile('foto_penceramah')) {
                // hapus gambar penceramah lama
                // Penyimpanan::hapus('/public/foto_penceramah/' digabung value detail penceramah, column foto_penceramah
                Storage::delete('public/foto_penceramah/' . $penceramah->foto_penceramah);

                // lakukan upload gambar
                // $nama_foto_penceramah_baru misalnya berisi 1_0912345.jpg
                // value detail_penceramah, column penceramah_id, waktu() . '.' . $permintaan->file('foto_penceramah')->ekstensi();
                $nama_foto_penceramah_baru = $request->file('foto_penceramah')->hashName() . '.' . $request->file('foto_penceramah')->extension();
                // upload gambar dan ganti nama gambar
                // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                // argumen kedua adalah value input name="foto_penceramah"
                // argument ketiga adalah nama file gambar baru nya
                Storage::putFileAs('public/foto_penceramah/', $request->file('foto_penceramah'), $nama_foto_penceramah_baru);

                // berisi panggil gambar dan jalur nya
                $jalur_gambar = public_path("storage/foto_penceramah/$nama_foto_penceramah_baru");

                // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 1250 , dan tinggi nya 500, resize gambar juga termasuk kompres gambar
                $gambar = Image::make($jalur_gambar)->resize(1250, 500);

                // argument pertama pada save adalah simpan gambar dengan cara timpa file
                // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
                // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                $gambar->save($jalur_gambar, 100, 'jpg');
            } 
            // jika user tidak mengupload foto_penceramah lewat input name="foto_penceramah" maka pakai value column penceramah, column foto_penceramah
            // lain jika $permintaan tidak memiliki file dari input name="foto_penceramah"
            else if (!$request->hasFile('foto_penceramah')) {
                // berisi memanggil value detail penceramah, column foto_penceramah
                $nama_foto_penceramah_baru = $penceramah->foto_penceramah;
            };

            // Perbarui penceramah
            // value detail_penceramah, column user_id di table penceramah diisi dengan value detail_user yang login, column user_id
            $penceramah->user_id = Auth::user()->user_id;
            // panggil detail_penceramah, column nama_penceramah lalu diisi dengan input name="nama_penceramah"
            $penceramah->nama_penceramah = $request->nama_penceramah;
            // value detail_penceramah, column slug_penceramah diisi buat slug dari value $permintaan->nama_penceramah
            $penceramah->slug_penceramah = Str::slug($request->nama_penceramah, '-');
            // panggil detail_penceramah, column foto_penceramah lalu diisi dengan input value variable $nama_foto_penceramah_baru
            $penceramah->foto_penceramah = $nama_foto_penceramah_baru;
            // $request->input("post-trixFields") adalah kode dari https://github.com/amaelftah/laravel-trix
            // value detail_penceramah, column konten_penceramah diisi value input name="konten_penceramah"
            $penceramah->konten_penceramah = $request->konten_penceramah;
            // value detail_penceramah, column dipublikasi_pada diisi value input name="dipublikasi_pada"
            $penceramah->dipublikasi_pada = $request->dipublikasi_pada;
            // penceramah di perbarui
            $penceramah->update();

            // Lepaskan semua kategori dari penceramah atau hapus semua kategori dari penceramah
            $penceramah->kategori()->detach();

            // 1 penceramah bisa punya banyak kategori, jadi aku mengisi table penceramah_kategori, misalnya penceramah_id 1 berjudul "Ini adalah judul" lalu ada 2 kategori yaitu kategori_id yang berisi 1 dan 2
            // $penceramah->kategori()->menempelkan($permintaan->kategori_id)
            $penceramah->kategori()->attach($request->kategori_id);

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Kegatan gaji karyawan berhasil di perbarui" 
                'pesan' => "penceramah $request->nama_penceramah berhasil diperbarui.",
            ]);
        };
    }

    // Hapus beberapa penceramah yang di centang
    // $request berisi beberapa value input name="penceramah_ids[]" yang dibuat di penceramahController, method read, anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // berisi $permintaan->penceramah_ids atau value input name="penceramah_ids[]", anggaplah berisi ["1", "2"]
        $semua_penceramah_id = $request->penceramah_ids;

        // pengulangan untuksetiap
        // untukSetiap, $semua_penceramah_id sebagai $penceramah_id
        foreach ($semua_penceramah_id as $penceramah_id) {
            // ambil detail_penceramah
            // berisi model penceramah, dimana value column penceramah_id sama dengan $penceramah_id, ambil data baris pertama
            $detail_penceramah = Penceramah::where('penceramah_id', $penceramah_id)->first();

            // hapus gambar
            // Penyimpanan::hapus('/public/foto_penceramah/' digabung value detail_penceramah, column foto_penceramah
            Storage::delete('public/foto_penceramah/' . $detail_penceramah->foto_penceramah);

            // hapus penceramah 
            // panggil detail_penceramah lalu hapus
            $detail_penceramah->delete();
        };

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'pesan' => 'Berhasil menghapus penceramah  yang dipilih.'
        ]);
    }
}

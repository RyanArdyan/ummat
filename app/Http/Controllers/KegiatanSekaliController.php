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
use App\Models\KegiatanSekali;

class KegiatanSekaliController extends Controller
{
    // Method index menampilkan halaman kegiatan sekali
    // publik fungsi index
    public function index()
    {
        // berisi ambil value detail user yang autetikasi atau login, column is_admin
        $is_admin = Auth::user()->is_admin;

        // jika yang login adalah admin maka 
        // jika value variable is_admin nya sama dengan "1"
        if ($is_admin === "1") {
            // kembalikkan ke tampilan admin.kegiatan_sekali.index
            return view('admin.kegiatan_sekali.index');
        }
        // lain jika yang login adalah jamaah maka
        else if ($is_admin === "0") {
            // ambil semua kegiatan sekali, ambil data terbaru
            // berisi KegiatanSekali, di pesan oleh value column updated_at, data yang paling baru, dapatkan semua data nya
            $semua_kegiatan_sekali = KegiatanSekali::orderBy('updated_at', 'desc')->get();

            // kembalikkan ke tampilan jamaah.kegiatan_sekali.index, kirimkan data berupa array, 
            return view('jamaah.kegiatan_sekali.index', [
                // key semua_kegiatan_sekali berisi value $semua_kegiatan_sekali
                'semua_kegiatan_sekali' => $semua_kegiatan_sekali
            ]);
        };
    }

    // menampilkan semua data table kegiatan_sekali, yang column tipe_kegiatan nya berisi "Kegiatan sekali".
    public function read()
    {
        // ambil semua value dari column kegiatan_sekali_id, nama_kegiatan dan lain-lain dimana value column tipe_kegiatan sama dengan "Kegiatan sekali", dapatkan semua data nya
        // beriisi KegiatanSekali::pilih('kegiatan_sekali_id', 'nama_kegiatan', 'dan-lain-lain') dimana value column tipe_kegiatan sama dengan value 'Kegiatan sekali', dapatkan()
        $semua_kegiatan = KegiatanSekali::select('kegiatan_sekali_id', 'nama_kegiatan', 'gambar_kegiatan', 'tanggal', 'jam_mulai', 'jam_selesai')->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_kegiatan
        return DataTables::of($semua_kegiatan)
            // nomor kegiatan
            // tambah index column
            ->addIndexColumn()
            // ulang detail_kegiatan menggunakan $kegiatan
            // tambah column pilih, jalankan fungsi, KegiatanSekali $kegiatan
            ->addColumn('select', function(KegiatanSekali $kegiatan) {
                // return element html
                // name="kegiatan_sekali_ids[]" karena name akan menyimpan array yang berisi beberapa kegiatan_sekali_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column kegiatan_sekali_id
                return '
                        <input name="kegiatan_sekali_ids[]" value="' . $kegiatan->kegiatan_sekali_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('gambar_kegiatan', function(KegiatanSekali $kegiatan) {
                // buat img, yg attribute src nya memanggil public/storage/gambar_kegiatan/$kegiatan->gambar_kegiatan, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img src='/storage/gambar_kegiatan_sekali/$kegiatan->gambar_kegiatan' width='50px' height='50px'>";

            })
            // buat tombol edit
            // tambahKolom('aksi', fungsi(Kegiatan $kegiatan))
            ->addColumn('action', function(KegiatanSekali $kegiatan) {
                // panggil url /kegiatan-sekali/edit/ lalu kirimkan value kegiatan_sekali_id nya agar aku bisa mengambil detail kegiatan_sekali berdasarkan kegiatan_sekali_id
                return  "
                    <a href='/kegiatan-sekali/edit/$kegiatan->kegiatan_sekali_id' class='btn btn-warning btn-sm'>
                        <i class='fas fa-pencil-alt'></i> Edit
                    </a>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat elemnt html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'gambar_kegiatan', 'action'])
        // buat benar
        ->make(true);
    }

    // method buat untuk menampilkan formulir tambah kegiatan
    // publik fungsi buat()
    public function create()
    {
        // kembalikkan ke tampilan admin.kegiatan_sekali.formulir_create
        return view('admin.kegiatan_sekali.formulir_create');
    }

    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator buat untuk semua permintaan
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
            $file_gambar = Storage::putFileAs('public/gambar_kegiatan_sekali/', $request->file('gambar_kegiatan'), $nama_gambar_baru);

            // berisi panggil gambar dan jalur nya
            $jalur_gambar = public_path("storage/gambar_kegiatan_sekali/$nama_gambar_baru");

            // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
            // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500, dan tinggi nya 285, resize gambar juga termasuk kompres gamabr
            $gambar = Image::make($jalur_gambar)->resize(500, 285);

            // argument pertama pada save adalah simpan gambar dengan cara timpa file
            // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
            // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
            $gambar->save($jalur_gambar, 100, 'jpg');

            // Simpan kegiatan_sekali ke table kegiatan_sekali
            // KegiatanSekali buat
            kegiatanSekali::create([
                // column nama_kegiatan di table kegiatan diisi dengan value input name="nama_kegiatan"
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal' => $request->tanggal,
                'gambar_kegiatan' => $nama_gambar_baru,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "kegiatan PT Bisa berhasil disimpan."
                'pesan' => "Kegiatan $request->nama_kegiatan berhasil disimpan.",
            ]);
        };
    }

    // method edit, $kegiatan_sekali_id itu fitur Pengikatan Model Rute jadi parameter $kegiatan_sekali_id berisi detail_kegiatan_id berdasarkan id yang dikirimkan
    public function edit(KegiatanSekali $kegiatan_sekali_id)
    {
        // kembalikkkan ke tampilan admin.kegiatan_sekali.formulir_edit, lalu kirimkan array yang berisi key detail_kegiatan_sekali berisi value variable $detail_kegiatan_sekali
        return view('admin.kegiatan_sekali.formulir_edit', ['detail_kegiatan_sekali' => $kegiatan_sekali_id]);
    }

    // method perbarui untuk memperbarui kegiatan sekali
    // parameter $permintaan berisi semua value input
    // $kegiatan_sekali_id berisi kegiatan_sekali_id yang dikirim url
    public function update(Request $request, $kegiatan_sekali_id)
    {
        // ambil detail kegiatan_sekali berdasarkan kegiatan_sekali_id
        // berisi KegiatanSekali dimana value column kegiatan_sekali_id sama dengan kegiatan_sekali_id, data baris pertama saja
        $detail_kegiatan = KegiatanSekali::where('kegiatan_sekali_id', $kegiatan_sekali_id)->first();

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_kegiatan harus wajib dan maksimal nya adalah 255
            'nama_kegiatan' => 'required|max:255',
            // value input name tanggal harus wajib
            'tanggal' => 'required',
            // value input name jam_mulai harus wajib
            'jam_mulai' => 'required',
            // value input name jam_selesai harus wajib
            'jam_selesai' => 'required',
            // value input name="gambar_kegiatan" harus berupa gambar
            'gambar_kegiatan' => 'image'
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
            // jika user mengganti atau mengupload gambar_kegiatan
            // jika ($permintaan->memilikiFile('gambar_kegiatan'))
            if ($request->hasFile('gambar_kegiatan')) {
                // hapus gambar kegiatan_sekali lama
                // Penyimpanan::hapus('/public/gambar_kegiatan_sekali/' digabung value detail_kegiatan, column gambar_kegiatan
                Storage::delete('public/gambar_kegiatan_sekali/' . $detail_kegiatan->gambar_kegiatan);

                // lakukan upload gambar
                // $nama_gambar_kegiatan_baru misalnya berisi 12345.jpg
                // waktu() . '.' . $permintaan->file('gambar_kegiatan')->ekstensi();
                $nama_gambar_kegiatan_baru = time() . '.' . $request->file('gambar_kegiatan')->extension();
                // upload gambar dan ganti nama gambar
                // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                // argumen kedua adalah value input name="gambar_kegiatan"
                // argument ketiga adalah nama file gambar baru nya
                $file_gambar = Storage::putFileAs('public/gambar_kegiatan_sekali/', $request->file('gambar_kegiatan'), $nama_gambar_kegiatan_baru);

                // berisi panggil gambar dan jalur nya
                $jalur_gambar = public_path("storage/gambar_kegiatan_sekali/$nama_gambar_kegiatan_baru");

                // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500, dan tinggi nya 285, resize gambar juga termasuk kompres gamabr
                $gambar = Image::make($jalur_gambar)->resize(500, 285);

                // argument pertama pada save adalah simpan gambar dengan cara timpa file
                // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
                // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                $gambar->save($jalur_gambar, 100, 'jpg');
            } 
            // jika user tidak mengupload gambar_kegiatan lewat input name="gambar_kegiatan" maka pakai value column detail_kegiatan, column gambar_kegiatan
            // lain jika $permintaan tidak memiliki file dari input name="gambar_kegiatan"
            else if (!$request->hasFile('gambar_kegiatan')) {
                // berisi memanggil value detail user, column gambar_kegiatan
                $nama_gambar_kegiatan_baru = $detail_kegiatan->gambar_kegiatan;
            };

            // Perbarui kegiatan
            // panggil detail_kegiatan, column nama_kegiatan lalu diisi dengan input name="nama_kegiatan"
            $detail_kegiatan->nama_kegiatan = $request->nama_kegiatan;
            $detail_kegiatan->gambar_kegiatan = $nama_gambar_kegiatan_baru;
            $detail_kegiatan->tanggal = $request->tanggal;
            $detail_kegiatan->jam_mulai = $request->jam_mulai;
            $detail_kegiatan->jam_selesai = $request->jam_selesai;
            // detail_kegiatan di perbarui
            $detail_kegiatan->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Kegatan gaji karyawan berhasil di perbarui" 
                'pesan' => "Kegiatan $request->nama_kegiatan berhasil diperbarui.",
            ]);
        };
    }

    // Hapus beberapa kegiatan_sekali yang di centang
    // $request berisi beberapa value input name="kegiatan_sekali_ids[]" yang dibuat di KegiatanSekaliController, method read, anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // berisi $permintaan->kegiatan_sekali_ids atau value input name="kegiatan_sekali_ids[]", anggaplah berisi ["1", "2"]
        $semua_kegiatan_sekali_id = $request->kegiatan_sekali_ids;

        // pengulangan untuksetiap
        // untukSetiap, $semua_kegiatan_sekali_id sebagai $kegiatan_sekali_id
        foreach ($semua_kegiatan_sekali_id as $kegiatan_sekali_id) {
            // ambil detail_kegiatan_sekali
            // berisi model KegiatanSekali, dimana value column kegiatan_sekali_id sama dengan $kegiatan_sekali_id, ambil data baris pertama
            $detail_kegiatan_sekali = KegiatanSekali::where('kegiatan_sekali_id', $kegiatan_sekali_id)->first();

            // hapus gambar
            // Penyimpanan::hapus('/public/gambar_kegiatan_sekali/' digabung value detail_kegiatan, column gambar_kegiatan
            Storage::delete('public/gambar_kegiatan_sekali/' . $detail_kegiatan_sekali->gambar_kegiatan);

            // hapus kegiatan sekali
            // panggil detail_kegiatan_sekali lalu hapus
            $detail_kegiatan_sekali->delete();
        };

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'pesan' => 'Berhasil menghapus kegiatan sekali yang dipilih.'
        ]);
    }
}
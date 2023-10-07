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
use App\Models\KegiatanRutin;

class KegiatanRutinController extends Controller
{
    // Method index menampilkan halaman kegiatan rutin
    // publik fungsi index
    public function index()
    {
        // kembalikkan ke admin.kegiatan_rutin.index
        return view('admin.kegiatan_rutin.index');
    }

    // menampilkan semua data di table kegiatan_rutin
    public function read()
    {
        // ambil semua value dari column kegiatan_rutin_id dan lain-lain, data terbaru akan tampil di urutan pertama
        // beriisi KegiatanRutin::pilih('kegiatan_rutin_id', 'nama_kegiatan', 'dan-lain-lain') dimana value column tipe_kegiatan sama dengan value 'Kegiatan Rutin', dapatkan()
        $semua_kegiatan = KegiatanRutin::select('kegiatan_rutin_id', 'nama_kegiatan', 'gambar_kegiatan', 'hari', 'jam_mulai', 'jam_selesai')
            // jadi senin akan tampil pertama, setelah itu barulah selasa dan seterus nya
            // dipesanOlehMentah, bidang, column hari, value senin, selasa dan seterus nya
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\"at', 'Sabtu', 'Minggu')")
            // jadi misalnya ada 2 kegiatan dari hari senin lalu ada jam 18.00 dan jam 19.00 maka jam 18.00 akan tampil diatas jam 19.00
            // dipesanOleh column jam_mulai, ascending atau naik
            ->orderBy('jam_mulai', 'ASC')
            // dapatkan semua data nya
            ->get();

        // syntax punya yajra
        // kembalikkan datatables dari semua_kegiatan
        return DataTables::of($semua_kegiatan)
            // nomor kegiatan
            // tambah index column
            ->addIndexColumn()
            // ulang detail_kegiatan menggunakan $kegiatan
            // tambah column pilih, jalankan fungsi, KegiatanRutin $kegiatan
            ->addColumn('select', function(KegiatanRutin $kegiatan) {
                // return element html
                // name="kegiatan_rutin_ids[]" karena name akan menyimpan array yang berisi beberapa kegiatan_rutin_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column kegiatan_rutin_id
                return '
                        <input name="kegiatan_rutin_ids[]" value="' . $kegiatan->kegiatan_rutin_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('gambar_kegiatan', function(KegiatanRutin $kegiatan) {
                // buat img, yg attribute src nya memanggil public/storage/gambar_kegiatan/$kegiatan->gambar_kegiatan, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img src='/storage/gambar_kegiatan_rutin/$kegiatan->gambar_kegiatan' width='50px' height='50px'>";

            })
            // buat tombol edit
            // tambahKolom('action', fungsi(Kegiatan $kegiatan))
            ->addColumn('action', function(KegiatanRutin $kegiatan) {
                // panggil url /kegiatan-rutin/edit/ lalu kirimkan value kegiatan_rutin_id nya agar aku bisa mengambil detail kegiatan_rutin berdasarkan kegiatan_rutin_id
                return  "
                    <a href='/admin/kegiatan-rutin/edit/$kegiatan->kegiatan_rutin_id' class='btn btn-warning btn-sm'>
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
        // kembalikkan ke tampilan admin.kegiatan_rutin.formulir_create
        return view('admin.kegiatan_rutin.formulir_create');
    }

    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator dibuat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_kegiatan harus wajib, unik atau tidak boleh sama dan maksimal nya adalah 255
            'nama_kegiatan' => 'required|unique:kegiatan_rutin|max:255',
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
            // $nama_gambar_baru misalnya berisi tokomu_3242312345.jpg
            // $permintaan->file('gambar_kegiatan')->hashNama();
            $nama_gambar_baru = "tokomu_" . $request->file('gambar_kegiatan')->hashName();
            // upload gambar dan ganti nama gambar
            // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
            // argumen kedua adalah value input name="gambar_kegiatan"
            // argument ketiga adalah nama file gambar baru nya
            $file_gambar = Storage::putFileAs('public/gambar_kegiatan_rutin/', $request->file('gambar_kegiatan'), $nama_gambar_baru);

            // berisi panggil gambar dan jalur nya
            $jalur_gambar = public_path("storage/gambar_kegiatan_rutin/$nama_gambar_baru");

            // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
            // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500, dan tinggi nya 285, resize gambar juga termasuk kompres gamabr
            $gambar = Image::make($jalur_gambar)->resize(500, 285);

            // argument pertama pada save adalah simpan gambar dengan cara timpa file
            // argument kedua pada save adalah kualitas nya aku turunkan sedikit menjadi 90% agar terkompress, 
            // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
            $gambar->save("$jalur_gambar", 90, 'jpg');

            // Simpan kegiatan ke table kegiatan
            // kegiatan buat
            kegiatanRutin::create([
                // column nama_kegiatan di table kegiatan diisi dengan value input name="nama_kegiatan"
                'nama_kegiatan' => $request->nama_kegiatan,
                'gambar_kegiatan' => $nama_gambar_baru,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai
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

    // method edit, paramter $kegiatan_rutin_id itu fitur Pengikatan Model Rute jadi parameter $kegiatan_rutin_id berisi detail_kegiatan_rutin berdasarkan id yang dikirimkan
    // url nya kegiatan_rutin_id jadi parameter nya $kegiatan_rutin_id
    public function edit(KegiatanRutin $kegiatan_rutin_id)
    {
        // kembalikkkan ke tampilan admin.kegiatan_rutin.formulir_edit, lalu kirimkan array yang berisi key detail_kegiatan berisi value parameter $kegiatan_rutin_id
        return view('admin.kegiatan_rutin.formulir_edit', ['detail_kegiatan' => $kegiatan_rutin_id]);
    }

    // method perbarui untuk memperbarui kegiatan rutin
    // parameter $permintaan berisi semua value input
    // $kegiatan_rutin_id berisi kegiatan_rutin_id yang dikirim url
    public function update(Request $request, $kegiatan_rutin_id)
    {
        // ambil detail kegiatan berdasarkan kegiatan_rutin_id
        // berisi KegiatanRutin dimana value column kegiatan_rutin_id sama dengan paramter $kegiatan_rutin_id, ambil data baris pertama saja
        $detail_kegiatan = KegiatanRutin::where('kegiatan_rutin_id', $kegiatan_rutin_id)->first();

        // jika value input name="nama_kegiatan" sama dengan value detail_kegiatan, column nama_kegiatan berarti user tidak mengubah nama_kegiatan nya maka
        if ($request->nama_kegiatan === $detail_kegiatan->nama_kegiatan) {
            // value input name="nama_kegiatan" harus diisi, maksimal nya 255
            $validasi_nama_kegiatan = 'required|max:255';
        }
        // lain jika value input name="nama_kegiatan" tidak sama dengan value detail_kegiatan, column nama_kegiatan berarti user mengubah nama_kegiatan nya
        else if ($request->nama_kegiatan !== $detail_kegiatan->nama_kegiatan) {
            // value input name="nama_kegiatan" harus diisi, unik atau tidak boleh sama, maksimal nya 255
            $validasi_nama_kegiatan = "required|unique:kegiatan_rutin|max:255";
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name="nama_kegiatan" harus mengikuti aturan dari variable 
            'nama_kegiatan' => $validasi_nama_kegiatan,
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
            // jika user mengganti gambar_kegiatan
            // jika ($permintaan->memilikiFile('gambar_kegiatan'))
            if ($request->hasFile('gambar_kegiatan')) {
                // hapus gambar_kegiatan lama
                // Penyimpanan::hapus('/public/gambar_kegiatan_rutin/' digabung value detail_kegiatan, column gambar_kegiatan
                Storage::delete('public/gambar_kegiatan_rutin/' . $detail_kegiatan->gambar_kegiatan);


                 // lakukan upload gambar
                // $nama_gambar_kegiatan_baru misalnya berisi tokomu_23412345.jpg
                // $permintaan->file('gambar_kegiatan')->hashNama();
                $nama_gambar_kegiatan_baru = "tokomu_" . $request->file('gambar_kegiatan')->hashName();
                // upload gambar dan ganti nama gambar
                // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                // argumen kedua adalah value input name="gambar_kegiatan"
                // argument ketiga adalah nama file gambar baru nya
                $file_gambar = Storage::putFileAs('public/gambar_kegiatan_rutin/', $request->file('gambar_kegiatan'), $nama_gambar_kegiatan_baru);

                // berisi panggil gambar dan jalur nya
                $jalur_gambar = public_path("storage/gambar_kegiatan_rutin/$nama_gambar_kegiatan_baru");

                // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 500, dan tinggi nya 285, resize gambar juga termasuk kompres gamabr
                $gambar = Image::make($jalur_gambar)->resize(500, 285);

                // argument pertama pada save adalah simpan gambar dengan cara timpa file
                // argument kedua pada save adalah kualitas nya tidak aku turunkan, jadi hanya terkompress ketika resize 
                // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                $gambar->save("$jalur_gambar", 100, 'jpg');
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
            $detail_kegiatan->hari = $request->hari;
            $detail_kegiatan->jam_mulai = $request->jam_mulai;
            $detail_kegiatan->jam_selesai = $request->jam_selesai;
            // detail_kegiatan di perbarui
            $detail_kegiatan->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Pengelueran gaji karyawan berhasil di perbarui" 
                'pesan' => "kegiatan $request->nama_kegiatan berhasil diperbarui.",
            ]);
        };
    }

    // Hapus beberapa kegiatan_rutin yang di centang
    // parameter $request berisi beberapa value input name="kegiatan_rutin_ids[]" yang dibuat di KegiatanRutinController, method read, anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // berisi $permintaan->kegiatan_rutin_ids atau value input name="kegiatan_rutin_ids[]", anggaplah berisi ["1", "2"]
        $kegiatan_rutin_ids = $request->kegiatan_rutin_ids;

        // looping $kegiatan_rutin_ids agar mengambil setiap baris kegiatan_rutin yang sesuai
        foreach ($kegiatan_rutin_ids as $kegiatan_rutin_id) {
            // anggaplah di baris 1 pengulangan ada "1", di baris 2 pengulangan ada "2"
            // berisi ambil detail_kegiatan_rutin dimana value column kegiatan_rutin_id sama dengan variable $kegiatan_rutin_id, ambil data baris pertama
            $detail_kegiatan_rutin = KegiatanRutin::where('kegiatan_rutin_id', $kegiatan_rutin_id)->first();
            // hapus gambar_kegiatan_rutin
            // Penyimpanan::hapus('/public/gambar_kegiatan_rutin/' digabung value detail_kegiatan_rutin, column gambar_kegiatan
            Storage::delete('public/gambar_kegiatan_rutin/' . $detail_kegiatan_rutin->gambar_kegiatan);

            // hapus detail_kegiatan_rutin
            $detail_kegiatan_rutin->delete();
        };

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'pesan' => 'Berhasil menghapus kegiatan rutin yang dipilih.'
        ]);
    }
}

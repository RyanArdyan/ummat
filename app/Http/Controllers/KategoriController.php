<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// gunakan atau import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index() {
        // berisi ambil value detail user yang autetikasi atau login, column is_admin
        $is_admin = Auth::user()->is_admin;

        // jika yang login adalah admin maka 
        // jika value variable is_admin nya sama dengan "1"
        if ($is_admin === "1") {
            // kembalikkan ke tampilan admin.kategori.index
            return view('admin.kategori.index');
        }
        // lain jika yang login adalah jamaah maka
        else if ($is_admin === "0") {
            // kembalikkan ke tampilan jamaah.kategori.index
            return view('jamaah.kategori.index');
        };
    }

    // menampilkan semua data table kategori
    public function read()
    {
        // ambil semua value dari column kategori_id dan nama_kategori
        // berisi Kategori::pilih('kategori_id', 'nama_kategori', 'dan-lain-lain'), dipesan oleh column updated_at, dari z ke a jadi data terbaru dulu yang tampil, dapatkan semua data nya
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori', 'slug_kategori')->orderBy('updated_at', 'desc')->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_kategori
        return DataTables::of($semua_kategori)
            // nomor kategori
            // tambah index column
            ->addIndexColumn()
            // Jika menambah sebuah element html, melakukan relasi, memanggil helpers maka aku butuh addColumns
            // ulang detail_kategori menggunakan parameter $kategori
            // tambah column pilih, jalankan fungsi, kategori $kategori
            ->addColumn('select', function(kategori $kategori) {
                // return element html
                // name="kategori_ids[]" karena name akan menyimpan array yang berisi beberapa kategori_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column kategori_id
                return '
                        <input name="kategori_ids[]" value="' . $kategori->kategori_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            // buat tombol edit
            // tambahKolom('aksi', fungsi(kategori $kategori))
            ->addColumn('action', function(kategori $kategori) {
                // panggil url /kategori/edit/ lalu kirimkan value slug_kategori nya agar aku bisa mengambil detail kategori berdasarkan slug_kategori
                // jika ingin membuat attribute maka gunakan data-nama-attribute, data-kategori-id berisi setiap value detail_kategori, column slug_kategori
                return  "
                    <a href='/kategori/edit/$kategori->slug_kategori' class='btn btn-warning btn-sm mt-1'>
                        <i class='fas fa-pencil-alt'></i>
                    </a>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'action'])
        // buat benar
        ->make(true);
    }
    
    // method buat untuk menampilkan formulir tambah kategori
    // publik fungsi buat()
    public function create()
    {
        // kembalikkan ke tampilan admin.kategori.formulir_create
        return view('admin.kategori.formulir_create');
    }

    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_kategori harus wajib dan maksimal nya adalah 255
            'nama_kategori' => 'required|max:255',
        ]);

        // buat validasi
        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key pesan berisi pesan berikut
                'pesan' => 'Validasi Menemukan Error',
                // key errors berisi semua value input dan pesan yang error
                'errors' => $validator->errors()
            ]);
        }
        // jika validasi berhasil
        else {
            // berisi menangkap value input nama kategori atau input name="nama_kategori"
            $nama_kategori = $request->nama_kategori;
            // Simpan kategori ke table kategori
            // kategori buat
            Kategori::create([
                // column nama_kategori di table kategori diisi dengan value variable $nama_kategori
                'nama_kategori' => $nama_kategori,
                // column slug_kategori diisi buat slug dari value variable $nama_kategori
                'slug_kategori' => Str::slug($nama_kategori, '-')
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "kategori doa berhasil disimpan."
                'pesan' => "$request->nama_kategori berhasil disimpan.",
            ]);
        };
    }

     // method edit, paramter $kategori itu fitur Pengikatan Model Rute jadi parameter $kategori berisi detail_kategori_id berdasarkan id yang dikirimkan
     public function edit(kategori $kategori)
     {
         // kembalikkkan ke tampilan admin.kategori.formulir_edit, lalu kirimkan 
         return view('admin.kategori.formulir_edit', [
            // key detail_kategori berisi value variable $kategori yang berisi detail_kategori
            'detail_kategori' => $kategori
        ]);
     }

     // method perbarui untuk memperbarui kategori 
    // parameter $permintaan berisi semua value input atau value attribute name
    // parameter $kategori mengambil detail_kategori berdasarkan kategori_id karena menggunakan fitur pengikatan route model
    public function update(Request $request, kategori $kategori)
    {
        // jika nilai input nama_kategori sama dengan nilai column nama_kategori dari detail $kategori berarti user tidak mengubah nama_kategorinya nya
        if ($request->nama_kategori === $kategori->nama_kategori) {
            // nama_kategori harus wajib, string, minimal 3 dan maksimal 255
            $validasi_nama_kategori = 'required|max:255';
        }
        // lain jika value input name="nama_kategori" tidak sama dengan detail_kategori, column nama_kategori berarti user mengubah nama_kategori nya atau maka baris detail_kategori akan di skip datanya lalu value input nama_kategori tidak boleh sama dengan baris detail_kategori, column nama_kategori lain nya
        else if ($request->nama_kategori !== $kategori->nama_kategori) {
            // validasi nama_kategori harus unique, wajib, string, max 255 
            $validasi_nama_kategori = 'unique:kategori|required|max:255';
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_kategori harus mengikuti aturan dari value variable $validasi_nama_kategori
            'nama_kategori' => $validasi_nama_kategori
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

            // Perbarui kategori
            // panggil detail_kategori, column nama_kategori lalu diisi dengan input name="nama_kategori"
            $kategori->nama_kategori = $request->nama_kategori;
            // detail kategori di perbarui
            $kategori->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "kategori doa berhasil di perbarui" 
                'pesan' => "$request->nama_kategori berhasil diperbarui.",
            ]);
        };
    }

    // Hapus beberapa kategori yang di centang
    // $request berisi beberapa value input name="kategori_ids[]" yang dibuat di kategoriController, method read, anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // berisi $permintaan->kategori_ids atau value input name="kategori_ids[]", anggaplah berisi ["1", "2"] di dapatkan dari kategoriController, method read dan script
        $semua_kategori_id = $request->kategori_ids;

        // metode whereIn menghapus element dari kumpulan yang tidak memiliki nilai item tertentu yang terkandung dalam larik yang diberikan
        // $saringan_kategori berisi Semua kategori, dimana dalam column kategori_id berisi value yang sama dengan value variable $semua_kategori_id
        $filtered_kategori = Kategori::whereIn("kategori_id", $semua_kategori_id);
        // panggil $saringan_kategori lalu hapus semua datanya
        $filtered_kategori->delete();

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key pesan berisi value string berikut
            'pesan' => 'Berhasil menghapus kategori yang dipilih.'
        ]);
    }
}

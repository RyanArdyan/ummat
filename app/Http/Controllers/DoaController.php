<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// gunakan atau import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
// package laravel datatables
use DataTables;
use App\Models\Doa;

class DoaController extends Controller
{
    // panggil method ini jika data ada table doa itu kosong, jadi jika aku php artisan migrate:fresh --seed, aku harus panggil route untuk memanggil method ini
    // aku mengambil data doa dari api external yaitu https://github.com/farizdotid/ lalu aku menyimpan ke database
    public function simpan_data_doa_dari_api_external()
    {
        // berisi mengambil data doa dari api external, aku dapat dari https://github.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA
        // berisi http dapatkan data dari url berikut, lalu ubah data nya menjadi json
        $semua_doa = Http::get('https://doa-doa-api-ahmadramadhan.fly.dev/api')->json();


        // looping semua doa
        // untukSetiap $semua_doa sebagai $doa
        foreach ($semua_doa as $doa) {
            // lakukan penyimpan data terhadap semua doa misalnya 32
            Doa::create([
                'nama_doa' => $doa["doa"],
                "bacaan_arab" => $doa["ayat"],
                "bacaan_latin" => $doa["latin"],
                "arti_doanya" => $doa["artinya"]
            ]);
        };

        // berisi mengambil semua data doa dari table doa
        // berisi model doa, ambil semua data
        $semua_doa_dari_table_doa = Doa::all();

        // kembalikkan tanggapan berupa json lalu kirimkan value data berupa array
        return response()->json([
            // key message berisi value string berikut
            'message' => 'Berhasil menyimpan data doa dari api external',
            // key semua_doa berisi value variable berikut
            'semua_doa' => $semua_doa_dari_table_doa
        ]);
    }

    // Jika yang login adalah admin maka menampilkan halaman doa saja dan jika yg login adalah jamaah maka maka akan menapilkan halaman doa dan mengirimkan semua data doa
    public function index() {
        // berisi ambil value detail user yang autetikasi atau login, column is_admin
        $is_admin = Auth::user()->is_admin;

        // mengambil semua data doa, data terbaru akan tampil di paling atas
        // berisi Doa dipesan oleh column diperbarui_pada, menurun, dapatkan semua datanya
        $semua_doa = Doa::orderBy('updated_at', 'desc')->get();

        // mengambil data semua doa dari table doa

        // jika yang login adalah admin maka 
        // jika value variable is_admin nya sama dengan "1"
        if ($is_admin === "1") {
            // kembalikkan ke tampilan admin.doa.index lalu kirimkan data berupa array
            return view('admin.doa.index');
        }
        // lain jika yang login adalah jamaah atau value variable is_admin sama dengan 0 maka
        else if ($is_admin === "0") {
            // kembalikkan ke tampilan jamaah.doa.index, kirimkan data berupa array, 
            return view('jamaah.doa.index', [
                // key semua_doa berisi value $semua_doa
                'semua_doa' => $semua_doa
            ]);
        };
    }

    // menampilkan semua data table doa
    public function read()
    {
        // ambil semua value dari column doa_id dan nama_doa
        // berisi Doa::pilih('doa_id', 'nama_doa', 'dan-lain-lain'), dipesan oleh column updated_at, dari z ke a jadi data terbaru dulu yang tampil, dapatkan semua data nya
        $semua_doa = Doa::select('doa_id', 'nama_doa')->orderBy('updated_at', 'desc')->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_doa
        return DataTables::of($semua_doa)
            // nomor doa
            // tambah index column
            ->addIndexColumn()
            // ulang detail_doa menggunakan $doa
            // tambah column pilih, jalankan fungsi, Doa $doa
            ->addColumn('select', function(Doa $doa) {
                // return element html
                // name="doa_ids[]" karena name akan menyimpan array yang berisi beberapa doa_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value detail_doa, column doa_id
                return '
                        <input name="doa_ids[]" value="' . $doa->doa_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            // buat tombol edit
            // tambahKolom('aksi', fungsi(doa $doa)), variable $doa berisi setiap value doa.
            ->addColumn('action', function(Doa $doa) {
                // jika ingin membuat attribute maka gunakan data-nama-attribute, data-doa-id berisi setiap value detail_doa, column doa_id
                return  "
                    <button type='button' class='tombol_detail_doa btn btn-sm btn-success mt-1' data-doa-id='$doa->doa_id' data-toggle='modal' data-target='.modal_detail_doa'>
                        <i class='mdi mdi-eye'></i>
                    </button>

                    <a href='/doa/edit/$doa->doa_id' class='btn btn-warning btn-sm mt-1'>
                        <i class='fas fa-pencil-alt'></i>
                    </a>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat elemnt html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'action'])
        // buat benar
        ->make(true);
    }

    // method show untuk menampilkan detail_doa berdasarkan doa_id
    // parameter doa berisi value detail_doa, aku menggunkana fitur pengikatan route model
    public function show(Doa $doa) {
        return response()->json([
            'detail_doa' => $doa
        ]);
    }
    
    // method buat untuk menampilkan formulir tambah doa
    // publik fungsi buat()
    public function create()
    {
        // kembalikkan ke tampilan admin.doa.formulir_create
        return view('admin.doa.formulir_create');
    }

    // parameter $permintaan berisi semua value input, attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_doa harus wajib, minimal 3 dan maksimal nya adalah 255 dan harus unik atau value nya tidak boleh sama
            'nama_doa' => 'required|min:3|max:255|unique:doa',
            // value input name bacaan_arab harus wajib dan harus unique
            'bacaan_arab' => 'required|unique:doa',
            // value input name bacaan_latin harus wajib dan harus unique
            'bacaan_latin' => 'required|unique:doa',
            // value input name arti_doanya harus wajib dan maksimal nya adalah 255
            'arti_doanya' => 'required',
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
            // berisi mengambil data doa dari api external, aku dapat dari https://github.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA
            // berisi http dapatkan data dari url berikut, lalu ubah data nya menjadi json
            $semua_doa = Http::get('https://doa-doa-api-ahmadramadhan.fly.dev/api')->json();

            // array ini digunakan untuk menampung setiap value detail_doa, column doa dari api external
            $array_doa = [];

            // looping semua_doa dari api external agar aku tidak bisa menambahkan doa yang sudah add di api external
            // untukSetiap $semua_doa sebagai $doa
            foreach($semua_doa as $doa) {
                // mengubah value detail_doa, column doa menjadi huruf kecil semua misalnya "Doa sebelum tidur" menjadi "doa sebelum tidur"
                // berisi mengubah setiap detail_doa, column oda menjadi huruf kecil semua
                $doa = strtolower($doa["doa"]);

                // dorong setiap value variable $doa ke dalam array $array_doa
                // array_
                // array_push($array_doa, $doa);

                // ubah string di value input name="nama_doa" menjadi huruf kecil semua misalnya "Doa Makan" menjadi "doa makan"
                // berisi string ke bawah dari value input name="nama_doa"
                $value_input_nama_doa = strtolower($request->nama_doa);

                // jika value variable doa sama dengan value variable $value_input_nama_doa
                if ($doa === $value_input_nama_doa) {
                    // kembalikkan tanggapana berupa json lalu kirimkan data berupa array
                    return response()->json([
                        "pesan" => "Doa itu sudah ada"
                    ]);
                };
            };

            // Simpan doa ke table doa
            // Doa buat
            Doa::create([
                // column nama_doa di table doa diisi dengan value input name="nama_doa"
                'nama_doa' => $request->nama_doa,
                'bacaan_arab' => $request->bacaan_arab,
                'bacaan_latin' => $request->bacaan_latin,
                'arti_doanya' => $request->arti_doanya,
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "doa abc Bisa berhasil disimpan."
                'pesan' => "$request->nama_doa berhasil disimpan.",
            ]);
        };
    }

     // method edit, $doa itu fitur Pengikatan Model Rute jadi parameter $doa berisi detail_doa_id berdasarkan id yang dikirimkan
     public function edit(Doa $doa)
     {
         // kembalikkkan ke tampilan admin.doa.formulir_edit, lalu kirimkan array yang berisi key detail_doa berisi value variable $doa yang berisi detail_doa
         return view('admin.doa.formulir_edit', [
            'detail_doa' => $doa
        ]);
     }

     // method perbarui untuk memperbarui doa 
    // parameter $permintaan berisi semua value input atau value attribute name
    // parameter $doa mengambil detail_doa berdasarkan doa_id karena menggunakan fitur pengikatan route model
    public function update(Request $request, Doa $doa)
    {
        // jika nilai input nama_doa sama dengan nilai column nama_doa dari detail $doa berarti user tidak mengubah nama_doanya nya
        if ($request->nama_doa === $doa->nama_doa) {
            // nama_doa harus wajib, string, minimal 3 dan maksimal 255
            $validasi_nama_doa = 'required|string|min:3|max:255';
        }
        // lain jika input nama_doa tidak sama dengan detail_doa column nama_doa berarti user mengubah nama nya
        else if ($request->nama_doa !== $doa->nama_doa) {
            // validasi nama_doa wajib, string, max 255  dan harus unik dari detail doa
            $validasi_nama_doa = 'required|string|min:3|max:255|unique:doa';
        };

        // jika nilai input bacaan_arab sama dengan nilai column bacaan_arab dari detail $doa berarti user tidak mengubah bacaan_arabnya nya
        if ($request->bacaan_arab === $doa->bacaan_arab) {
            // bacaan_arab harus wajib, string
            $validasi_bacaan_arab = 'required|string';
        }
        // lain jika input bacaan_arab tidak sama dengan detail_doa column bacaan_arab berarti user mengubah nama nya
        else if ($request->bacaan_arab !== $doa->bacaan_arab) {
            // validasi bacaan_arab wajib, string, min 3, dan harus unik dari detail doa
            $validasi_bacaan_arab = 'required|string|unique:doa';
        };

        // jika nilai input bacaan_latin sama dengan nilai column bacaan_latin dari detail $doa berarti user tidak mengubah bacaan_latinnya nya
        if ($request->bacaan_latin === $doa->bacaan_latin) {
            // bacaan_latin harus wajib, string
            $validasi_bacaan_latin = 'required|string';
        }
        // lain jika input bacaan_latin tidak sama dengan detail_doa column bacaan_latin berarti user mengubah nama nya
        else if ($request->bacaan_latin !== $doa->bacaan_latin) {
            // validasi bacaan_latin wajib, string, min 3, dan harus unik dari detail doa
            $validasi_bacaan_latin = 'required|string|unique:doa';
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name nama_doa harus mengikuti aturan dari value variable $validasi_nama_doa
            'nama_doa' => $validasi_nama_doa,
            // value input name bacaan_arab harus mengikuti aturan dari value variable $validasi_bacaan_arab
            'bacaan_arab' => $validasi_bacaan_arab,
            // value input name bacaan_latin harus mengikuti aturan dari value variable $validasi_bacaan_latin
            'bacaan_latin' => $validasi_bacaan_latin,
            // value input name arti_doanya harus mengikuti aturan diisi
            'arti_doanya' => "required",
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

            // Perbarui doa
            // panggil detail_doa, column nama_doa lalu diisi dengan input name="nama_doa"
            $doa->nama_doa = $request->nama_doa;
            // panggil detail_doa, column bacaan_arab lalu diisi dengan input name="bacaan_arab"
            $doa->bacaan_arab = $request->bacaan_arab;
            // panggil detail_doa, column bacaan_latin lalu diisi dengan input name="bacaan_latin"
            $doa->bacaan_latin = $request->bacaan_latin;
            // panggil detail_doa, column arti_doanya lalu diisi dengan input name="arti_doanya"
            $doa->arti_doanya = $request->arti_doanya;
            // doa di perbarui
            $doa->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Doa sebelum tidur berhasil di perbarui" 
                'pesan' => "$request->nama_doa berhasil diperbarui.",
            ]);
        };
    }

    // Hapus beberapa doa yang di centang
    // $request berisi beberapa value input name="doa_ids[]" yang dibuat di DoaController, method read, anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // berisi $permintaan->doa_ids atau value input name="doa_ids[]", anggaplah berisi ["1", "2"] di dapatkan dari DoaController, method read dan script
        $semua_doa_id = $request->doa_ids;

        // metode whereIn menghapus element dari kumpulan yang tidak memiliki nilai item tertentu yang terkandung dalam larik yang diberikan
        // $saringan_doa berisi Semua Doa, dimana dalam column doa_id berisi value yang sama dengan value variable $semua_doa_id
        $filtered_doa = Doa::whereIn("doa_id", $semua_doa_id);
        // panggil $saringan_doa lalu hapus semua datanya
        $filtered_doa->delete();

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key pesan berisi value string berikut
            'pesan' => 'Berhasil menghapus Doa yang dipilih.'
        ]);
    }
}

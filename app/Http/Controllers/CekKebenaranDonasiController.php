<?php

// kelompokkan package
namespace App\Http\Controllers;

// import atau gunakan
use Illuminate\Http\Request;
// package laravel datatables untuk menghubungkan package laravel datatables dengan package datatables
use DataTables;
// panggil model
use App\Models\DonasiManual;

class CekKebenaranDonasiController extends Controller
{
    // method index
    public function index()
    {
        // kembalikkan ke tampilan berikut
        return view('admin.cek_kebenaran_donasi_manual.index');
    }

    // menampilkan semua data table donasi_manual yg column status nya berisi "Belum Cek"
    public function read()
    {
        // jadi ambil semua_donasi_manual yg value column statusnya berisi "Belum Cek" lalu data terbaru akan tampil yang pertama
        // berisi donasi_manual pilih semua value column donasi_manual_id dan lain-lain, dimana value column status sama dengan "Belum Cek", dipesanOleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_donasi_manual = DonasiManual::select('donasi_manual_id', 'user_id', 'foto_bukti', 'jumlah_donasi', 'pesan_donasi', 'tipe_pembayaran')->where('status', 'Belum Cek')->orderBy("updated_at", "desc")->get();

        // syntax punya yajra
        // kembalikkan datatables dari semua_donasi_manual
        return DataTables::of($semua_donasi_manual)
            // nomor donasi_manual
            // tambah index column
            ->addIndexColumn()
            // tambah column foto_bukti, jalankan fungsi, parameter $donasi_manual berisi setiap value detail_donasi_manual
            ->addColumn('foto_bukti', function(DonasiManual $donasi_manual) {
                // buat img, yg attribute src nya memanggil public/storage/donasi_manual/ value dari $donasi_manual->donasi_manual, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img data-donasi-manual-id='$donasi_manual->donasi_manual_id' src='/storage/donasi_manual/$donasi_manual->foto_bukti' class='foto_bukti jadikan_pointer' width='50px' height='50px'>";
            })
            // alasan tambahKolom nama_pendonasi karena aku butuh panggil relasi nya yaitu info pendonasi nya
            ->addColumn('nama_pendonasi', function(DonasiManual $donasi_manual) {
                // kembalikkan value detail_donasi_manual, yang berelasi dengan table users, column name, dengan cara panggil models DonasiManual, method user()
                return $donasi_manual->user->name;
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x foto_bukti dan lain-lain
        ->rawColumns(['foto_bukti', 'nama_pendonasi'])
        // buat benar
        ->make(true);
    }

    // method untuk menampilkan foto bukti donasi secara penuh, ada satu parameter yaitu $donasi_manual_id misalnya berisi 1
    public function lihat_foto_bukti($donasi_manual_id)
    {
        // ambil value detail_donasi_manual, column foto_bukti
        // berisi ambil value detail donasi_manual, dimana value column donasi_manual_id, sama dengan value parameter $donasi_manual_id, ambil baris pertama, ambil value column foto_bukti
        $foto_bukti = DonasiManual::where('donasi_manual_id', $donasi_manual_id)->first()->foto_bukti;
        // kembalikkan ke tampilan berikut lalu kirimkan data berupa array
        return view('admin.cek_kebenaran_donasi_manual.lihat_foto_bukti', [
            // kunci foto_bukti berisi value variable $foto_bukti
            'foto_bukti' => $foto_bukti,
            'donasi_manual_id' => $donasi_manual_id
        ]);
    }

    // method proses_pengecekan_status, jadi jika user click tombol "Benar" maka ubah value detail_donasi_manual, column status menjadi "Benar", kalau user click tombol "Palsu" maka hapus value detail_donasi
    // $request berisi menangkap data yg dikirim script
    public function proses_pengecekan_status(Request $request) {
        // berisi tangkap value $permintaan->status yg dikirim script
        $status = $request->status;
        $donasi_manual_id = $request->donasi_manual_id;
        // berisi panggil donasi_manual, dimana value column donasi_manual_id sama dengan value variable $donasi_manual_id, ambil data baris pertama ditemukan
        $detail_donasi_manual = DonasiManual::where('donasi_manual_id', $donasi_manual_id)->first();

        // jika value $status sma dengan "benar" maka
        if ($status === "Benar") {
            // panggil value detail_donasi_manual lalu perbarui value column status menjadi "Benar"
            $detail_donasi_manual->status = "Benar";
            // panggil value detail_donasi_manual lalu simpan perubahan atau update
            $detail_donasi_manual->save();

            // kembalikkan tanggapan berupa json dari $request->status
            return response()->json([
                // key pesan berisi value 200
                'status' => 200,
                'pesan' => "Donasi yang dilakukan adalah benar"
            ]);
        }
        // lain jika value variable status nya adalah "Palsu"
        if ($status === "Palsu") {
            // hapus value detail_donasi
            // panggil value variable detaiL_donasi_manual, lalu hapus barisnya
            $detail_donasi_manual->delete();

            // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
            return response()->json([
                // key pesan berisi value 200
                'status' => 200,
                'pesan' => 'Donasi yang dilakukan adalah palsu'
            ]);
        };
    }

    // jadi setelah admin mengecek dan mengubah value column status, misalnya menjadi "Benar" maka tampilkan notifikasi "Terima kasih sudah mengecek, donasi yang tadi adalah benar"
    public function tampilkan_notifikasi($status_donasi) {
        // jika value $status_donasi sama dengan 'Donasi nya benar' maka
        if ($status_donasi === 'Donasi nya benar') {
            // kembalikkan alihkan ke route berikut lalu kirimkan data sesi yang di flash
            return redirect()->route('admin.cek_kebenaran_donasi_manual.index')->with('status', 'Terima kasih sudah mengecek, donasi yang tadi adalah benar');
        }
        // lain jika value $status_donasi sama dengan 'Donasi nya palsu' maka
        else if ($status_donasi === 'Donasi nya palsu') {
            // kembalikkan alihkan ke route berikut lalu kirimkan data sesi yang di flash
            return redirect()->route('admin.cek_kebenaran_donasi_manual.index')->with('status', 'Terima kasih sudah mengecek, donasi yang tadi adalah palsu');
        }

    }
}



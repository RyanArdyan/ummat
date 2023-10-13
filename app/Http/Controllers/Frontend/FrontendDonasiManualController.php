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
// package laravel datatables untuk menghubungkan package laravel datatables dengan package datatables
use DataTables;
use App\Models\DonasiManual;

class FrontendDonasiManualController extends Controller
{
    // ada 2 parameter yaitu tanggal_awal pastinya tanggal 1 dan tanggal_akhir itu maksudnya tanggal hari ini
    public function data($tanggal_awal, $tanggal_akhir)
    {
        // ambil semua DonasiManual 
        // berisi ambil value table DonasiManual, pilih column donasi_manual_id, dll, dimanaAntara value column dibuat_pada, dari tanggal_awal sampai tanggal_akhir dimana value column status berisi 'Benar', dipesan oleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_donasi_manual = DonasiManual::select('donasi_manual_id', 'user_id', 'jumlah_donasi', 'pesan_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Benar')->orderBy("updated_at", "desc")->get();


        // untuk membuat looping nomor
        $nomor = 1;
        // buat wadah sebagai wadah dari data
        $wadah = [];

        // lakukan pengulangan dari variable $semua_donasi_manual
        // untukSetiap ($semua_donasi_manual sebagai $donasi_manual) 
        foreach ($semua_donasi_manual as $donasi_manual) {
            // buat row sebagai wadah
            $row = [];
            // mulai push data ke array row, index DT_RowIndex
            // panggil array $row, key DT_RowIndex diisi value variable $nomor++
            $row["DT_RowIndex"] = $nomor++;
            // array row, key pendonasi diisi value detail_donasi yang berelasi dengan table user, column name
            $row["pendonasi"] = $donasi_manual->user->name;
            // key jumlah_donasi diisi panggil helper rupiah_bentuk, value detail_donasi, column jumlah_donasi
            $row["jumlah_donasi"] = rupiah_bentuk($donasi_manual->jumlah_donasi);
            $row["pesan_donasi"] = $donasi_manual->pesan_donasi;
            // panggil array $wadah lalu diisi dengan value array $row
            $wadah[] = $row;
        };

        // jumlahkan value column jumlah_donasi yg terpilih
        // DonasiManual pilih value column jumlah_donasi, dimana value column dibuat_pada berisi value variable $tanggal_awal sampai variable $tanggal_akhir dimana value column status sama dengan 'Benar', jumlahkan value column jumlah_donasi yg terpilih
        $total_donasi = DonasiManual::select('jumlah_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Benar')->sum("jumlah_donasi");

        // Isi array ke dalam array $wadah
        // panggil array $wadah lalu pada anak terakhir dibuat sebuah array
        $wadah[] = [
            // key DT_RowIndex diisi string berikut
            "DT_RowIndex" => "",
            'pendonasi' => "Total Donasi",
            // panggil helper rupiah_bentuk lalu kirimkan alue variable total_donasi
            'jumlah_donasi' => rupiah_bentuk($total_donasi),
            'pesan_donasi' => ""
        ];

        // kembalikkan value variable $wadah
        return $wadah;
    }

    // menampilkan semua data table donasi_manual pada bulan ini
    public function read()
    {

        // berisi tanggal awal pada bulan ini misalnya 2023-08-01
        $tanggal_awal = date('Y-m-01');

        // berisi tanggal akhir atau tanggal hari ini
        // berisi format yang diinginkan (tahun-bulan-tanggal jam:menit:detik)
        $tanggal_akhir = date('Y-m-d H:i:s');

        // berisi panggil method data yg berada diluar lalu kirimkan 2 argument untuk mengambil data, anggaplah berisi tr
        $data = $this->data($tanggal_awal, $tanggal_akhir);

        // kembalikkan databtable dari value $data
        return DataTables::of($data)
            // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
            // mentahKolom2x pendasi, dan lain-lain
            ->rawColumns(['pendonasi', 'jumlah_donasi'])
            // buat benar
            ->make(true);
    }

    

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
        // custom Terjamahan validasi 
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

        // upload gambar dan ganti nama gambar
        // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
        // argumen kedua adalah value input name="foto_bukti"
        // argument ketiga adalah nama file gambar baru nya
        Storage::putFileAs('public/donasi_manual/', $request->file('foto_bukti'), $nama_gambar_baru);

        // Simpan donasi ke table donasi_manual
        // donasi_manual, buat
        $detail_donasi = DonasiManual::create([
            // column user_id di table donasi diisi dengan value variable user_id
            'user_id' => $user_id,
            'foto_bukti' => $nama_gambar_baru,
            'jumlah_donasi' => $jumlah_donasi,
            'pesan_donasi' => $pesan_donasi,
            'nomor_wa' => $nomor_wa_user,
            'status' => 'Belum Cek',
            'tipe_pembayaran' => $tipe_pembayaran
        ]);


        // kembalikkan ke tampilan berikut lalu kirimkan data berupa array
        return view('frontend.donasi_manual.selesai', [
            // kunci pesan_notifikasi berisi string berikut
            'pesan_notifikasi' => 'Terima kasih, foto bukti  donasi yang anda upload sedang di cek admin, tolong gunakan aplikasi ini dengan bijak atau jangan upload foto bukti palsu.'
        ]);
    }


}

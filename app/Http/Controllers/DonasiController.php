<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// import atau gunakan
// memperluas kelas dasar
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// aku kustom atau membuat validasi formulir sendiri
use App\Rules\ValidasiNomorWhatsapp;
// package laravel datatables untuk menghubungkan package laravel datatables dengan package datatables
use DataTables;
use App\Models\Donasi;
// package barryvdh/laravel-dompdf untuk berurusan pdf
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class DonasiController extends Controller
{
    // menampilkan halaman donasi
    public function index()
    {
        // tanggal awal pada bulan saat ini, sudah pasti dimulai dari tanggal 1
        // y = year, m = month, d = day
        $tanggal_awal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        // misalnya hari tanggal 20 maka berisi 20 lah
        $tanggal_hari_ini = date("Y-m-d");

        // kembalikkan ke tampilan admin.donasi.index
        return view('admin.donasi.index', [
            // key tanggal_awal berisi value variable $tanggal_awal
            'tanggal_awal' => $tanggal_awal,
            'tanggal_hari_ini' => $tanggal_hari_ini
        ]);
    }

    // ada 2 parameter
    public function data($tanggal_awal, $tanggal_akhir)
    {
        // ambil semua donasi 
        // berisi ambil value table donasi, pilih column donasi_id, dll, dimanaAntara value column dibuat_pada, dari tanggal_awal sampai tanggal_akhir dimana value column status berisi 'Sudah Bayar', dipesan oleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_donasi = Donasi::select('donasi_id', 'user_id', 'jumlah_donasi', 'pesan_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Sudah Bayar')->orderBy("updated_at", "desc")->get();


        // untuk membuat looping nomor
        $nomor = 1;
        // buat wadah sebagai wadah dari data
        $wadah = [];

        // lakukan pengulangan dari variable $semua_donasi
        // untukSetiap ($semua_donasi sebagai $donasi) 
        foreach ($semua_donasi as $donasi) {
            // buat row sebagai wadah
            $row = [];
            // mulai push data ke array row
            // panggil array $row, key DT_RowIndex diisi value variable $nomor++
            $row["DT_RowIndex"] = $nomor++;
            // array row, key pendonasi diisi value detail_donasi yang berelasi dengan table user, column name
            $row["pendonasi"] = $donasi->user->name;
            // key jumlah_donasi diisi value detail_donasi, column jumlah_donasi
            $row["jumlah_donasi"] = rupiah_bentuk($donasi->jumlah_donasi);
            // key pesan_donasi diisi value detail_donasi, column pesan_donasi
            $row["pesan_donasi"] = $donasi->pesan_donasi;
            // panggil array $wadah lalu diisi dengan value array $row
            $wadah[] = $row;
        };

        // jumlah value column jumla donasi yg terpilih
        // berisi ambil value column jumlah_donasi dimana value column dibuat_pada berisi value variable $tanggal_awal sampai variable $tanggal_akhir dimana value column status sama dengan 'Sudah Bayar', dipesan oleh column diperbarui_pada, jumlahkan value column jumlah_donasi yg terpilih
        $total_donasi = Donasi::select('jumlah_donasi')->whereBetween("created_at", [$tanggal_awal, $tanggal_akhir])->where('status', 'Sudah Bayar')->sum("jumlah_donasi");

        // Isi array ke dalam array $wadah
        // panggil array $wadah lalu diisi dengan array
        $wadah[] = [
            // key DT_RowIndex diisi string berikut
            "DT_RowIndex" => "",
            'pendonasi' => "Total Donasi",
            // tutup string lalu gabungkan dengan kode php lalu gabungkan lagi dengan html
            // panggil helper rupiah_bentuk lalu kirimkan alue variable total_donasi
            'jumlah_donasi' => rupiah_bentuk($total_donasi),
            'pesan_donasi' => ""
        ];

        // kembalikkan value variable $wadah
        return $wadah;
    }

    // menampilkan semua data table donasi pada bulan ini
    public function read()
    {

        // berisi tanggal awal pada bulan ini misalnya 2023-08-01
        $tanggal_awal = date('Y-m-01');

        // berisi tanggal akhir
        // berisi format yang diinginkan (tahun-bulan-tanggal jam:menit:detik)
        $tanggal_akhir = date('Y-m-d H:i:s');

        // berisi panggil method data yg berada diluar lalu kirimkan 2 argument untuk mengambil data, anggaplah berisi tr
        $data = $this->data($tanggal_awal, $tanggal_akhir);


        // kembalikkan databtable dari value $wqadah
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

        // kembalikkan ke tampilan frontend.donasi.formulir_create, lalu kirimkan data berupa array
        return view('frontend.donasi.formulir_create', [
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
        // berisi ya waktu saat ini versi mentah misalnya berisi 
        $waktu_sekarang = Carbon::now();
        // Menambahkan 23 jam dan 30 menit ke waktu saat ini
        $waktu_berikutnya = $waktu_sekarang->addHours(23)->addMinutes(30);
        // Ini tanggal kadaluarsa anggaplah berisi "2023-09-17"
        $tanggal_kadaluarsa = $waktu_berikutnya->format('Y-m-d');
        // ini jam kadaluarsa anggaplah berisi "20:23:51"
        $jam_kadaluarsa = $waktu_berikutnya->format('H:i:s');

        // Simpan donasi ke table donasi
        // donasi, buat
        $detail_donasi = Donasi::create([
            // column user_id di table donasi diisi dengan value variable user_id
            'user_id' => $user_id,
            // column jumlah_donasi di table donasi diisi dengan value variable $jumlah_donasi
            'jumlah_donasi' => $jumlah_donasi,
            // column pesan_donasi di table donasi diisi dengan value variable $pesan_donasi
            'pesan_donasi' => $pesan_donasi,
            'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
            'jam_kadaluarsa' => $jam_kadaluarsa
        ]);

        // kode berikut didapatkan dari dokumentasi midtrans atau buka url berikut: https://docs.midtrans.com/docs/snap-snap-integration-guide atau cari di menu Built-in Interface (SNAP)/Integration Guide
        // Setel Kunci Server merchant Anda
        // panggil config/midtrans, key SERVER_ID
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Setel ke Lingkungan Pengembangan/Sandbox (default). Setel ke true untuk Lingkungan Produksi atau jika sudah di hosting (terima transaksi nyata).
        // ::adalahProduksi
        \Midtrans\Config::$isProduction = false;
        // Aktifkan sanitasi (default)
        // ::disanitasi
        \Midtrans\Config::$isSanitized = true;
        // Setel transaksi 3DS untuk kartu kredit ke true
        \Midtrans\Config::$is3ds = true;

        // kode midtrans, variable parameter berisi array, hanya boleh mengubah value dari key.
        $params = array(
            // key detail_transaksi berisi array
            'transaction_details' => array(
                // column pesanan_id berisi value variable detail-donasi, column donasi_id
                'order_id' => $detail_donasi->donasi_id,
                //  "jumlah kotor" adalah jumlah sebelum dilakukan pengurangan atau potongan apapun
                // jumlah_kotor diisi value variable detail_donasi, column jumlah_donasi
                'gross_amount' => $detail_donasi->jumlah_donasi,
            ),
            // key pelanggan_detail berisi array
            'customer_details' => array(
                // key first_name berisi value variable column name milik table user yg berelasi dengan table donasi
                'first_name' => $nama_user,
                'last_name' => "",
                'email' => $email_user,
                'phone' => $nomor_wa_user,
            ),
        );

        // $jepretToken = snap, dapatkan jepret token dari variable $params, contoh isinya adalah cc83938b-9b5f-46a9-bc74-44e38fa07fa4, isinya akan berubah-ubah
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Perbarui value detail_donasi, column snap_token diisi value variable snapToken
        $detail_donasi->snap_token = $snapToken;
        // panggil value detail_donasi, lalu update
        $detail_donasi->update();


        // kembalikkan lalu alihkan ke route donasi.detail lalu kirimkan data berupa array
        return redirect()->route('donasi.detail', [
            // key donasi_id berisi value detail_donasi, column donasi_id
            'donasi' => $detail_donasi->donasi_id,
            // key jepretToken berisi value variable $snapToken
            'snapToken' => $snapToken
        ])
        // Mengarahkan Ulang Dengan Data Sesi yang Di-Flash
        // dengan variable status berisi value berikut
        ->with('status', 'Silahkan lakukan pembayaran donasi.');
    }

    // method detail akan menampilkan halaman detail dari detail_donasi
    // parameter $donasi berisi value detail_donasi karena aku menggunakan fitur pengikatan route model, parameter $snapToken berisi menangkap value snapToken
    public function detail(Donasi $donasi, $snapToken)
    {
        // berisi value detail_user yg login, column nama
        // berisi value dari autentikasi, pengguna, name
        $nama_user_yg_login = Auth()->user()->name;

        // berisi ambil value detail_donasi, column donasi_id
        $donasi_id = $donasi->donasi_id;
        // berisi ambil value detail_donasi, column jumlah_donasi
        $jumlah_donasi = $donasi->jumlah_donasi;
        // berisi ambil value detail_donasi, column pesan_donasi
        $pesan_donasi = $donasi->pesan_donasi;

        // kembalikkan ke tampilan frontend.donasi.detail lalu kirimkan data berupa array
        return view("frontend.donasi.detail", [
            // key snapToken berisi value parameter $snapToken
            'snapToken' => $snapToken,
            // kunci donasi_id berisi value dari variable donasi_id
            'donasi_id' => $donasi_id,
            // kunci jumlah_donasi berisi panggil fungsi rupiah_bentuk lalu kirimkan value variable $jumlah_donasi
            'jumlah_donasi' => rupiah_bentuk($jumlah_donasi),
            // kunci pesan_donasi berisi value dari variable pesan_donasi
            'pesan_donasi' => $pesan_donasi,
            // kunci nama_user_yg_login berisi value dari variable $nama_user_yg_login
            'nama_user_yg_login' => $nama_user_yg_login
        ]);
    }

    // parameter $request berisi value dari key data milik script
    public function ubah_periode(Request $request)
    {
        // berisi tanggal awal
        // berisi $permintaan->tanggal_awal digabung 00:00:00
        $tanggal_awal = $request->tanggal_awal . ' 00:00:00';

        // berisi tanggal akhir
        // berisi $permintaan->tanggal_akhir digabung 00:00:00
        $tanggal_akhir = $request->tanggal_akhir . ' 00:00:00';

        // berisi panggil method data yg berada diluar lalu kirimkan 2 argument untuk mengambil data, anggaplah berisi tr
        $data = $this->data($tanggal_awal, $tanggal_akhir);

        // kembalikkan databtable dari value variable $data
        return DataTables::of($data)
            // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat element html maka harus dimasukkan ke dalam mentahColumn2x
            // mentahKolom2x pendonasi, dan lain-lain
            ->rawColumns(['pendonasi', 'jumlah_donasi'])
            // buat benar
            ->make(true);
    }

    // 2 parameter menangkap value dari 2 argument yg dikirim dari route 
    public function ekspor_pdf($parameter_tanggal_awal, $parameter_tanggal_akhir)
    {
        // date_default_timezone_set('Asia/Jakarta');
        
        // berisi tanggal awal
        // berisi value parameter berikut digabung 00:00:00
        $tanggal_awal = $parameter_tanggal_awal . ' 00:00:00';

        // berisi tanggal akhir
        // berisi value parameter berikut digabung 00:00:00
        $tanggal_akhir = $parameter_tanggal_akhir . ' 00:00:00';

        // berisi panggil method data yg berada diluar lalu kirimkan 2 argument untuk mengambil data, anggaplah berisi tr
        $data = $this->data($tanggal_awal, $tanggal_akhir);

        // PDF::muatTampilan('admin.donasi.cetak_pdf') kirimkan data berupa array
        $pdf = PDF::loadView('admin.donasi.cetak_pdf', [
            // key tanggal_awal berisi value variable tanggal_awal_format
            'tanggal_awal' => $parameter_tanggal_awal,
            'tanggal_akhir' => $parameter_tanggal_akhir,
            'data' => $data
        ]);
        // atur kertas menggunkan A4, bentuk potrait atau lebih ke horizontal
        $pdf->setPaper('a4', 'potrait');
        // jika disimpan maka nama filenya adalah laporan-pendapatan-tahun-bulan-tanggal
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function menunggu_pembayaran()
    {
        // ya berisi tanggal sekarang
        $tanggal_sekarang = Carbon::now()->format('Y-m-d');
        // ya berisi jam sekarang
        $jam_sekarang = Carbon::now()->format('H:i:s');

        // aku harus ambil value column user_id yang login
        $user_id = Auth::user()->user_id;


        // KODE INI BELUM DI CEK
        // KODE INI BELUM DI CEK
        // KODE INI BELUM DI CEK
        // KODE INI BELUM DI CEK
        // KODE INI BELUM DI CEK
        // ambil donasi yg value column user_id nya sama dengan value variable user_id, yg value column status nya sama dengan 'Belum Bayar', dimana value column tanggal_selesai, lebih besar atau sama dengan value variable tanggal_sekarang, pilih value column berikut, dapatkan semua datanya
        $beberapa_menunggu_pembayaran = Donasi::where('user_id', $user_id)->where('status', 'Belum Bayar')->whereDate('tanggal_kadaluarsa', '>=', $tanggal_sekarang)->select('donasi_id', 'user_id', 'jumlah_donasi', 'pesan_donasi', 'tanggal_kadaluarsa', 'jam_kadaluarsa', 'snap_token')->get();


        // jika value variable $beberapa_menunggu_pembayaran nya kosong atau berisi [] maka
        if (count($beberapa_menunggu_pembayaran) === 0) {
            // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
            return view('frontend.donasi.menunggu_pembayaran', [
                // key message berisi pesan berikut
                'message' => 'Menunggu pembayaran akan muncul jika anda melakukan donasi tapi belum melakukan pembayaran donasi'
            ]);
        } 
        // lain jika value variable berikut tidak sama dengan 0 berarti ada isi nya 
        else if ($beberapa_menunggu_pembayaran !== 0) {
            // kembalikkan tanggapan berupa json dari array berikut
            return view('frontend.donasi.menunggu_pembayaran', [
                // key message berisi pesan berikut
                'message' => 'Ada donasi yang harus dibayar',
                // key beberapa_menunggu_pembayaran berisi value variable berikut
                'beberapa_menunggu_pembayaran' => $beberapa_menunggu_pembayaranz
            ]);
        };
    }
}


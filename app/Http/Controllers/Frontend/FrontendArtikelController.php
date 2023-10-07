<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Postingan;
use App\Models\Komentar;
use Illuminate\Support\Facades\Validator;

class FrontendArtikelController extends Controller
{
    // Menampilkan halaman frontend doa 
    public function index()
    {
        // ambil semua postingan, data postingan terbaru akan tampil sebagai data pertama
        // berisi postingan, pilih value columns berikut, dipesan oleh column dipublikasi_pada, menurun, dapatkan semua data nya
        $semua_postingan = Postingan::select('judul_postingan', 'slug_postingan', 'gambar_postingan', 'dipublikasi_pada', 'user_id')->orderBy('dipublikasi_pada', 'desc')->get();

        // kembalikkan ke tampilan frontend/artikel/index lalu kirimkan data berupa array
        return view('frontend.artikel.index', [
            // kunci semua_postingan berisi value variable $semua_postingan
            'semua_postingan' => $semua_postingan
        ]);
    }

    // method show, parameter $postingan itu fitur Pengikatan Model Rute jadi parameter $postingan berisi detail_postingan berdasarkan slug_postingan yang dikirimkan, harus $postingan karena aku menulis {postingan:slug_postingan} di route nya
    public function show(Postingan $postingan)
    {
        // ambil detail komentar baru dari postingan yg sesuai
        // berisi komentar dimana value column postingan_id sama dengan value detail_postingan, column postingan_id, dipesan oleh column dibuat_pada, menurun, ambil data baris pertama
        $detail_komentar_terbaru = Komentar::where('postingan_id', $postingan->postingan_id)->orderBy('created_at', 'desc')->first();

        // kembalikkkan ke tampilan frontend.artikel.detail_artikel, lalu kirimkan data berupa array
        return view('frontend.artikel.detail_artikel', [
            // key detail_komentar_terbaru berisi value variable $detail_komentar_terbaru
            'detail_komentar_terbaru' => $detail_komentar_terbaru,
            // key detail_postingan berisi value parameter $detail_postingan
            'detail_postingan' => $postingan
        ]);
    }

    // parameter $permintaan berisi semua value attribute name
    public function simpan_komentar(Request $request)
    {
        // validasi semua input yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name komentarnya harus wajib
            'komentarnya' => 'required',
        ]);

        // buat validasi
        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key pesan berisi pesan berikut
                'pesan' => 'Validasi Menemukan Error'
            ]);
        }
        // jika validasi berhasil
        else {
            // berisi menangkap value input name="komentarnya"
            $komentarnya = $request->komentarnya;
            $postingan_id = $request->postingan_id;
            // berisi value column user_id yang login atau autentikasi, pengguna, pengguna_id
            $user_id = Auth::user()->user_id;

            // kategori buat
            Komentar::create([
                // value colum user_id diisi value variable $user_id
                'user_id' => $user_id,
                'postingan_id' => $postingan_id,
                'komentarnya' => $komentarnya,
                // column parent_id diisi value input name="parent_id, JIKA ADA!!!!!
                'parent_id' => $request->parent_id
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi string berikut
                'pesan' => "Komentar berhasil disimpan.",
            ]);
        };

        // Aku ingin halaman penceramah menampilkan table saja ah, males gua.
        // aku click penceramah, maka pindah ke route frontend.penceramah.index, buat FrontendPenceramahController

    }

    // Coba kosongkan komentar nya lalu click tombol selesai harus nya muncul validasi error. aku harus import sweetalert 2 cdn di dalam frontend/artikel/detail_artikel


    // method show, parameter $postingan_id berisi value postingan_id misalnya 1
    public function detail_komentar_terbaru($postingan_id)
    {
        // ambil detail komentar baru dari postingan yg sesuai
        // berisi komentar dimana value column postingan_id sama dengan value detail_postingan, column postingan_id atau parameter $postingan_id, dipesan oleh column dibuat_pada, menurun, ambil data baris pertama
        $detail_komentar_terbaru = Komentar::where('postingan_id', $postingan_id)->orderBy('created_at', 'desc')->first();

        // Jika tidak ada value di variable detail_komentar_terbaru
        if (!$detail_komentar_terbaru) {
            // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
            return response()->json([
                // key pesan berisi string berikut
                'message' => 'Belum ada komentar'
            ]);
        }
        // lain jika ada value di variable detail_komentar_terbaru
        else if ($detail_komentar_terbaru) {
            // kembalikkkan tanggapan berupa json lalu kirimkan data berupa array
            return response()->json([
                // key pesan berisi string berikut
                'message' => "Berhasil mengambil detail komentar terbaru beserta relasi nya",
                // key detail_komentar_terbaru berisi value variable $detail_komentar_terbaru
                'detail_komentar_terbaru' => $detail_komentar_terbaru
            ]);
        };
    }

    // menampilkan halaman semua komentar
    // parameter $postingan berisi value detail_postingan karena fitur pengikatan route model, harus $postingan karena
    public function halaman_semua_komentar(Postingan $postingan)
    {
        // kembalikkan ke tampilan frontend.artikel.semua_komentar lalu kirimkan data berupa array
        return view('frontend.artikel.semua_komentar', [
            // key detail_postingan berisi value parameter $postingan
            'detail_postingan' => $postingan
        ]);


    }

    // ambil semua komentar di postingan terkait
    // parameter $postingan berisi detail_postingan karena aku menggunakan fitur pengikatan route model
    public function read_semua_komentar(postingan $postingan)
    {
        // ambil semua komentar terkait di suatu postingan
        // berisi value detail_postingan yang berelasi dengan komentar
        $semua_komentar = $postingan->komentar;

        // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
        return response()->json([
            // key pesan berisi string berikut
            'message' => 'Berhasil mengambil semua komentar',
            // key semua_komentar berisi value variable semua_komentar
            'semua_komentar' => $semua_komentar
        ]);
    }
}

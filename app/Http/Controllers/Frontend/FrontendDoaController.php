<?php

namespace App\Http\Controllers\Frontend;

// perluas kelas dasar
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// package laravel datatables
use DataTables;
// import atau gunakan
use App\Models\Doa;

class FrontendDoaController extends Controller
{
    // Menampilkan halaman frontend doa 
    public function index()
    {
        // kembalikkan ke tampilan frontend/doa/index
        return view('frontend.doa.index');
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
            // buat tombol edit
            // tambahKolom('aksi', fungsi(doa $doa)), variable $doa berisi setiap value doa.
            ->addColumn('action', function(Doa $doa) {
                // jika ingin membuat attribute maka gunakan data-nama-attribute, data-doa-id berisi setiap value detail_doa, column doa_id
                return  "
                    <button type='button' class='tombol_detail_doa btn btn-sm btn-success mt-1' data-doa-id='$doa->doa_id' data-toggle='modal' data-target='.modal_detail_doa'>
                        Detail
                    </button>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat elemnt html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x action
        ->rawColumns(['action'])
        // buat benar
        ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// gunakan atau import
// perluas kelas dasar
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// package image intervention untuk kompress gambar, ubah lebar dan tinggi gambar dan lain-lain.
// image adalah alias yang di daftarkan di config/app
use Image;
// package laravel datatables
use DataTables;
use App\Models\Postingan;
use App\Models\Kategori;
use App\Models\Komentar;

class PostinganController extends Controller
{
    // Method index menampilkan halaman postingan
    // publik fungsi index
    public function index()
    {
        // kembalikkan ke tampilan admin.postingan.index
        return view('admin.postingan.index');
    }

    // menampilkan semua data table postingan
    public function read()
    {
        // berisi ambil value detail user yang autetikasi atau login,
        $detail_user_yg_login = Auth::user();

        // ambil semua postingan yg ditulis oleh user yg login
        // berisi ambil value detail_user_yg_login yg berelasi dengan postingan lewat Models/user, method postingan, dipesanOleh column diperbarui_pada, menurun, dapatkan semua data
        $semua_postingan = $detail_user_yg_login->postingan;


        // syntax punya yajra
        // kembalikkan datatables dari semua_postingan
        return DataTables::of($semua_postingan)
            // nomor postingan
            // tambah index column
            ->addIndexColumn()
            // ulang detail_postingan menggunakan $postingan
            // tambah column pilih, jalankan fungsi, Postingan $postingan
            ->addColumn('select', function(Postingan $postingan) {
                // return element html
                // name="postingan_ids[]" karena name akan menyimpan array yang berisi beberapa postingan_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil setiap value column postingan_id
                return '
                        <input name="postingan_ids[]" value="' . $postingan->postingan_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            // tambahKolom penulis, jalankan fungsi, parameter $postingan berisi setiap detail_postingan
            ->addColumn('penulis', function(Postingan $postingan) {
                // cetak value detail_postingan yang berelasi dengan detail_user, column name
                return $postingan->user->name;
            })
            // tambahKolom kategori, jalankan fungsi, parameter $postingan berisi setiap detail_postingan
            ->addColumn('kategori', function(Postingan $postingan) {
                // // berisi element div yang akan digunakan sebagai wadah dari element <p></p>
                $html = "<div>
                
                </div>";

                // looping, 1 postingan bisa punya banyak kategori
                foreach ($postingan->kategori as $kategori) {
                    // Cetak value setiap hubungan kategori yang berelasi dengan postingan, column nama_kategori
                    // jadi ul akan berisi li contohnya <ul><li></li></ul>
                    $html .= "<p class='badge badge-success m-0'>$kategori->nama_kategori</p>";
                };

                return $html;
            })
            ->addColumn('gambar_postingan', function(Postingan $postingan) {
                // buat img, yg attribute src nya memanggil public/storage/gambar_postingan/$postingan->gambar_postingan, / berarti panggil public, kutip dua bisa mencetak value variable
                return "<img src='/storage/gambar_postingan/$postingan->gambar_postingan' width='50px' height='50px'>";

            })
            // buat tombol edit
            // tambahKolom('aksi', fungsi(postingan $postingan)) parameter $postingan berisi setiap value detail_postingan
            ->addColumn('action', function(Postingan $postingan) {
                // panggil url /admin/postingan/edit/ lalu kirimkan value postingan_id nya agar aku bisa mengambil detail postingan berdasarkan postingan_id, aku akan gunakan fitur pengingakatan route model
                return  "
                    <a href='/admin/postingan/edit/$postingan->postingan_id' class='btn btn-warning btn-sm'>
                        <i class='fas fa-pencil-alt'></i> Edit
                    </a>
                ";
            })
        // jika sebuah column berisi relasi antar table, memanggil helpers dan membuat elemnt html maka harus dimasukkan ke dalam mentahColumn2x
        // mentahKolom2x select dan lain-lain
        ->rawColumns(['select', 'penulis', 'kategori', 'gambar_postingan', 'action'])
        // buat benar
        ->make(true);
    }


    // method buat untuk menampilkan formulir tambah postingan
    // publik fungsi buat()
    public function create()
    {
        // berisi models kategori pilih atau ambil semua value column kategori_id dan nama_kategori
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori')->get();
        // kembalikkan ke tampilan admin.postingan.formulir_create lalu kirimkan data berupa array
        return view('admin.postingan.formulir_create', [
            // key semua_kategori berisi value variable semua_kategori
            'semua_kategori' => $semua_kategori
        ]);
    }

    // method untuk mengecek apakah admin sudah membuat kategori sebelum dia ke halaman postingan/formulir_create
    public function cek_apakah_ada_kategori()
    {
        // ambil jumlah baris kategori
        // berisi kategori::jumlah()
        $jumlah_baris_kategori = Kategori::count();

        // jika value variable jumlah_baris_kategori sama dengan 0 maka
        if ($jumlah_baris_kategori === 0) {
            // kembalikkan tanggapan berupa json lalu kirimkan string berikut
            return response()->json("Anda harus membuat setidaknya satu kategori terlebih dahulu.");
        };
    }

    // upload images using the CKEditor(ckeditor.com) and laravel Spatie (spatie.be) packages
    public function upload_gambar(Request $request)
    {
        // // inisialisasi object model postingan agar bisa melakukan operasi database
        // $postingan = new Postingan();
        // // $postingan->postingan_id = 0;

        // // 'upload' adalah file yang di upload menggunakan CKEditor, cek di script
        // $images = $postingan->addMediaFromRequest('upload')->toMediaCollection('images');

        // return response()->json([
        //     'url' => $images->getUrl()
        // ]);


        // if the user uploads a file or image in ckeditor, assumed there is input name="upload"
        // if there is $request->hasFile('upload')
        // 'upload' is a file uploaded using CKEditor, check the script
        if ($request->hasFile('upload')) {
            // // contains the original name of the image, for example hello
            // $originName = $request->file('upload')->getClientOriginalName();
            // // contains information about image path
            // $fileName = pathinfo($originName, PATHINFO_FILENAME);
            // contains its original extension, for example jpg
            // $extension = $request->file('upload')->getClientOriginalExtension();
            $extension = 'jpg';
            // contains 'ummat' combined with '-' then time then a dot then the extension for example is hello_123321.jpg
            $fileName = 'ummat' . '_' . time() . '.' . $extension;
            
            // upload the image to the public/storage/gambar_konten_postingan folder
            // $request, file, assume input name="upload" created by CKEditor, then move to the folder path storage/gambar_postingan, then change the name of the file or image to the value of the variable $fileName
            $request->file('upload')->move(public_path('storage/gambar_konten_postingan'), $fileName);
            
            // for example contains http://127.0.0.1:8000/public/storage/gambar_postingan/hello_1343.jpg
            $url = asset('storage/gambar_konten_postingan/' . $fileName);
            // return the response in the form of json then send the data in the form of an array
            return response()->json([
                // filename key contains the variable value of $fileName
                'fileName' => $fileName, 
                // uploaded key contains 1 meaning true
                'uploaded'=> 1, 
                // The URL key contains the value variable $url
                'url' => $url
            ]);
        }
    }


    // parameter $permintaan berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua input yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name judul_postingan harus wajib, harus unik dan maksimal nya adalah 255
            'judul_postingan' => 'required|unique:postingan|max:255',
            // value input name kategori_id harus wajib
            'kategori_id' => 'required',
            "konten_postingan" => 'required',
            // value input name gambar_postingan harus wajib, harus berupa gambar.
            'gambar_postingan' => 'required|image',
            // value input dipublikasi_pada itu harus diisi
            'dipublikasi_pada' => 'required'
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
            // $nama_gambar_baru misalnya berisi tokomu_3242312345.jpg
            // $permintaan->file('gambar_kegiatan')->hashNama();
            $nama_gambar_baru = "tokomu_" . $request->file('gambar_postingan')->hashName();

            // upload gambar dan ganti nama gambar
            // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
            // argumen kedua adalah value input name="gambar_postingan"
            // argument ketiga adalah nama file gambar baru nya
            Storage::putFileAs('public/gambar_postingan/', $request->file('gambar_postingan'), $nama_gambar_baru);

            // berisi panggil gambar dan jalur nya
            $jalur_gambar = public_path("storage/gambar_postingan/$nama_gambar_baru");

            // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
            // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 1250 , dan tinggi nya 500, resize gambar juga termasuk kompres gambar
            $gambar = Image::make($jalur_gambar)->resize(1250, 500);

            // argument pertama pada save adalah simpan gambar dengan cara timpa file
            // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
            // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi jpg
            $gambar->save($jalur_gambar, 100, 'jpg');



            // Simpan postingan ke table postingan
            // Postingan buat
            $postingan = Postingan::create([
                // column user_id di table postingan diisi dengan value detail_user yang login, column user_id
                'user_id' => Auth::user()->user_id,
                // column judul_postingan di table postingan diisi dengan value input name="judul_postingan"
                'judul_postingan' => $request->judul_postingan,
                // column slug_postingan diisi buat slug dari value $permintaan->judul_postingan
                'slug_postingan' => Str::slug($request->judul_postingan, '-'),
                'gambar_postingan' => $nama_gambar_baru,
                // $request->input("post-trixFields") adalah kode dari https://github.com/amaelftah/laravel-trix
                'konten_postingan' => $request->konten_postingan,
                'dipublikasi_pada' => $request->dipublikasi_pada,
            ]);

            // 1 postingan bisa punya banyak kategori, jadi aku mengisi table postingan_kategori, misalnya postingan_id 1 berjudul "Ini adalah judul" lalu ada 2 kategori yaitu kategori_id yang berisi 1 dan 2
            // $postingan->kategori()->menempelkan($permintaan->kategori_id)
            $postingan->kategori()->attach($request->kategori_id);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "postingan PT Bisa berhasil disimpan."
                'pesan' => "Postingan $request->judul_postingan berhasil disimpan.",
            ]);
        };
    }

    // method show, parameter $postingan itu fitur Pengikatan Model Rute jadi parameter $postingan berisi detail_postingan berdasarkan slug_postingan yang dikirimkan
    public function show(Postingan $postingan)
    {
        // ambil detail komentar baru dari postingan yg sesuai
        // berisi komentar dimana value column postingan_id sama dengan value detail_postingan, column postingan_id, dipesan oleh column dibuat_pada, menurun, ambil data baris pertama
        $detail_komentar_terbaru = Komentar::where('postingan_id', $postingan->postingan_id)->orderBy('created_at', 'desc')->first();

        // kembalikkkan ke tampilan jamaah.postingan.detail_postingan, lalu kirimkan array
        return view('jamaah.postingan.detail_postingan', [
            // key detail_postingan berisi value variable $detail_postingan
            'detail_postingan' => $postingan,
            // key detail_komentar_terbaru berisi value variable $detail_komentar_terbaru
            'detail_komentar_terbaru' => $detail_komentar_terbaru
        ]);
    }

    // method edit, parameter $postingan itu fitur Pengikatan Model Rute jadi parameter $postingan_id berisi detail_postingan berdasarkan postingan_id yang dikirimkan
    public function edit(Postingan $postingan)
    {
        // kembalikkan tanggapna berupa json lalu kirimkan value parameter $postingan
        // return response()->json($postingan);

        // kembalikkan tanggapan berupa json lalu kirimkan value detail_postingan yang memiliki banyak kategori
        // return response()->json(['kategori_terpilih' => $postingan->kategori]);
        // 1 postingan punya banyak kategori. artinya aku mengambil kategori2x yang dipilih user
        $kategori_terpilih = $postingan->kategori;

        // kembalikkkan ke tampilan admin.postingan.formulir_edit, lalu kirimkan array
        return view('admin.postingan.formulir_edit', [
            // key detail_postingan berisi value variable $detail_postingan
            'detail_postingan' => $postingan,
            // key semua_kategori berisi 
            // berisi models kategori pilih atau ambil semua value column kategori_id dan nama_kategori
            'semua_kategori' => Kategori::select('kategori_id', 'nama_kategori')->get(),
            // key kategori_terpilih berisi value $postingan->kategori artinya aku mengirim kategori2x yang dipilih user
            'kategori_terpilih' => $kategori_terpilih
        ]);
    }

    // method perbarui untuk memperbarui postingan 
    // parameter $permintaan berisi semua value input
    // $postingan berisi mengambil detail_postingan berdasarkan postingan_id dari yang dikirim url
    public function update(Request $request, Postingan $postingan)
    {
        // jika nilai input judul_postingan sama dengan nilai column judul_postingan dari detail $postingan berarti user tidak mengubah judul_postingannya nya
        if ($request->judul_postingan === $postingan->judul_postingan) {
            // judul_postingan harus wajib, string, minimal 3 dan maksimal 255
            $validasi_judul_postingan = 'required|max:255';
        }
        // lain jika value input name="judul_postingan" tidak sama dengan detail_postingan, column judul_postingan berarti user mengubah judul_postingan nya atau maka baris detail_postingan akan di skip datanya lalu value input judul_postingan tidak boleh sama dengan baris detail_postingan, column judul_postingan lain nya
        else if ($request->judul_postingan !== $postingan->judul_postingan) {
            // validasi judul_postingan harus wajib, unik, max 255 
            $validasi_judul_postingan = 'required|unique:postingan|max:255';
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name judul_postingan mengikut aturan dari value variabel $validasi_judul_postingan
            'judul_postingan' => $validasi_judul_postingan,
            // value input name kategori_id harus wajib
            'kategori_id' => 'required',
            "konten_postingan" => 'required',
            // value input name gambar_postingan harus wajib, harus berupa gambar.
            'gambar_postingan' => 'image',
            // value input dipublikasi_pada itu harus diisi
            'dipublikasi_pada' => 'required'
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
            // jika user mengganti atau mengupload gambar_postingan
            // jika ($permintaan->memilikiFile('gambar_postingan'))
            if ($request->hasFile('gambar_postingan')) {
                // hapus gambar postingan lama
                // Penyimpanan::hapus('/public/gambar_postingan/' digabung value detail postingan, column gambar_postingan
                Storage::delete('storage/public/gambar_postingan/' . $postingan->gambar_postingan);

                // lakukan upload gambar
                // $nama_gambar_postingan_baru misalnya berisi tokomu_3242312345.jpg
                // $permintaan->file('gambar_kegiatan')->hashNama();
                $nama_gambar_postingan_baru = "tokomu_" . $request->file('gambar_postingan')->hashName();

                // upload gambar dan ganti nama gambar
                // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                // argumen kedua adalah value input name="gambar_postingan"
                // argument ketiga adalah nama file gambar baru nya
                Storage::putFileAs('public/gambar_postingan/', $request->file('gambar_postingan'), $nama_gambar_postingan_baru);

                // berisi panggil gambar dan jalur nya
                $jalur_gambar = public_path("storage/gambar_postingan/$nama_gambar_postingan_baru");

                // kode berikut di dapatkan dari https://image.intervention.io/v2/api/save
                // buka gambar dan atur ulang ukuran gambar atau kecilkan ukuran gambar menjadi lebar nya 1250 , dan tinggi nya 500, resize gambar juga termasuk kompres gambar
                $gambar = Image::make($jalur_gambar)->resize(1250, 500);

                // argument pertama pada save adalah simpan gambar dengan cara timpa file
                // argument kedua pada save adalah kualitas nya tidak aku turunkan karena 100% jadi terkompress hanya pada saat resize gambar
                // argument ketiga adalah ekstensi file nya akan menjadi jpg, jadi jika user mengupload png maka akan menjadi png
                $gambar->save($jalur_gambar, 100, 'jpg');
            } 
            // jika user tidak mengupload gambar_postingan lewat input name="gambar_postingan" maka pakai value column postingan, column gambar_postingan
            // lain jika $permintaan tidak memiliki file dari input name="gambar_postingan"
            else if (!$request->hasFile('gambar_postingan')) {
                // berisi memanggil value detail postingan, column gambar_postingan
                $nama_gambar_postingan_baru = $postingan->gambar_postingan;
            };

            // Perbarui postingan
            // value detail_postingan, column user_id di table postingan diisi dengan value detail_user yang login, column user_id
            $postingan->user_id = Auth::user()->user_id;
            // panggil detail_postingan, column judul_postingan lalu diisi dengan input name="judul_postingan"
            $postingan->judul_postingan = $request->judul_postingan;
            // value detail_postingan, column slug_postingan diisi buat slug dari value $permintaan->judul_postingan
            $postingan->slug_postingan = Str::slug($request->judul_postingan, '-');
            // panggil detail_postingan, column gambar_postingan lalu diisi dengan input value variable $nama_gambar_postingan_baru
            $postingan->gambar_postingan = $nama_gambar_postingan_baru;
            // $request->input("post-trixFields") adalah kode dari https://github.com/amaelftah/laravel-trix
            // value detail_postingan, column konten_postingan diisi value input name="konten_postingan"
            $postingan->konten_postingan = $request->konten_postingan;
            // value detail_postingan, column dipublikasi_pada diisi value input name="dipublikasi_pada"
            $postingan->dipublikasi_pada = $request->dipublikasi_pada;
            // postingan di perbarui
            $postingan->update();

            // Lepaskan semua kategori dari postingan atau hapus semua kategori dari postingan
            $postingan->kategori()->detach();

            // 1 postingan bisa punya banyak kategori, jadi aku mengisi table postingan_kategori, misalnya postingan_id 1 berjudul "Ini adalah judul" lalu ada 2 kategori yaitu kategori_id yang berisi 1 dan 2
            // $postingan->kategori()->menempelkan($permintaan->kategori_id)
            $postingan->kategori()->attach($request->kategori_id);

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Kegatan gaji karyawan berhasil di perbarui" 
                'pesan' => "Postingan $request->judul_postingan berhasil diperbarui.",
            ]);
        };
    }

    // Delete some checked posts
    // $request contains several input values name="posting_ids[]" created in PostsController, read method, assume it contains ["1", "2"]
    public function destroy(Request $request)
    {
        // contains $request->post_ids or input value name="post_ids[]", assume it contains ["1", "2"]
        $semua_postingan_id = $request->postingan_ids;

        // loop for each
        // do repetitions to get each post_id
        foreach ($semua_postingan_id as $postingan_id) {
            // fetch post_details
            // contains the Post model, where the column value of the post_id is the same as $post_id, take the first row of data
            $detail_postingan = Postingan::where('postingan_id', $postingan_id)->first();

            // strpos is used to search for a particular string within other stings 
            // if in the detail post value, content_post column there is <figure or if detail post value, content post column there is figure
            // the bad things is if the content text area only contains images and no text then he will not be able to delete the images
            if (strpos($detail_postingan->konten_postingan, '<figure') !== false) {
                // it defines a regular expression pattern (regex) that will be used to match string that containting image url with .jpg extension
                $pattern = '/src="[^"]*\/([^"]*\.jpg)"/';

                // it uses the preg_match function to match the regex patten with the content of $post_detail, post_content
                // if there is match, $match will be populated with the measurement results
                if (preg_match($pattern, $detail_postingan->konten_postingan, $matches)) {
                    // berisi nama file gambar
                    $nama_file_gambar = $matches[1];

                    // storage, delete a file stored in the following folder
                    Storage::delete('public/gambar_konten_postingan/' . $nama_file_gambar);
                };
            };

            // delete image
            // Storage::delete('/public/post_image/' combined value post_detail, column post_image
            Storage::delete('public/gambar_postingan/' . $detail_postingan->gambar_postingan);

            // delete post details
            // call post_details then delete
            $detail_postingan->delete();
        };

        // // return the response in json
        // return response()->json([
        //     // key status contains value 200
        //     'status' => 200,
        //     'pesan' => 'Berhasil menghapus postingan  yang dipilih.'
        // ]);
    }

    // parameter $permintaan berisi semua value attribute name
    public function simpan_komentar(Request $request)
    {
        // validasi semua input yang punya attribute name
        // berisi validator buat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // value input name komentarnya harus wajib dan maksimal nya adalah 255
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
            // berisi menangkap value input nama kategori atau input name="komentarnya"
            $komentarnya = $request->komentarnya;
            // kategori buat
            Komentar::create([
                // column user_id diisi value column user_id yang login
                'user_id' => Auth::user()->user_id,
                // column postingan_id diisi value input name="postingan_id"
                'postingan_id' => $request->postingan_id,
                // column komentarnya diisi value variable $komentarnya
                'komentarnya' => $komentarnya,
                // column parent_id diisi value input name="parent_id, jika ada
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
    }

    // menampilkan halaman semua komentar
    // parameter $postingan berisi value detail_postingan
    public function halaman_semua_komentar(Postingan $postingan)
    {
        // kembalikkan ke tampilan jamaah.postingan.semua_komentar lalu kirimkan data berupa array
        return view('jamaah.postingan.semua_komentar', [
            // key detail_postingan berisi value parameter $postingan
            'detail_postingan' => $postingan
        ]);
    }
}

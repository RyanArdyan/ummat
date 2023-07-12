{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Penceramah')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')
@endpush

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- nama_penceramah --}}
                <div class="form-group">
                    <label for="nama_penceramah">Nama Penceramah<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu nama_penceramah --}}
                    <input id="nama_penceramah" name="nama_penceramah" class="nama_penceramah_input input form-control" type="text"
                        placeholder="Masukkan Nama Penceramah" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="nama_penceramah_error pesan_error text-danger"></span>
                </div>

                {{-- foto_penceramah --}}
                <div class="form-group">
                    <label for="pilih_foto_penceramah">Foto Penceramah</label>
                    <br>
                    {{-- asset akan memanggil folder public --}}
                    <img id="pratinjau_foto_penceramah" src=""
                        alt="Foto Penceramah" width="150px" height="150px" class="mb-3 rounded">
                    <div class="input-group">
                        <div class="custom-file">
                            <input name="foto_penceramah" type="file" class="input foto_penceramah_input custom-file-input" id="pilih_foto_penceramah">
                            {{-- pesan error --}}
                            <label class="custom-file-label" for="foto_penceramah">Pilih file</label>
                        </div>
                    </div>
                    <span class="pesan_error foto_penceramah_error text-danger"></span>
                </div>

                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
                {{-- cetak panggil route penceramah.index --}}
                <a href="{{ route('penceramah.index') }}" class="btn btn-sm btn-danger">
                    <i class="mdi mdi-arrow-left">
                        Kembali
                    </i>
                </a>
            </form>
        </div>
    </div>
@endsection



{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <script>
        // tampilkan pratinjau foto ketika user mengubah foto
        // jika #pilih_foto_penceramah diubah maka jalankan fungsi berikut
        $("#pilih_foto_penceramah").on("change", function() {
            // ambil fotonya, this berarti #pilih_foto_penceramah, index ke 0
            let foto = this.files[0];
            // jika ada foto yang di pilih
            if (foto) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuat maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_foto_penceramah lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_foto_penceramah").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(foto);
            };
        });

        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // event atau acaa cegah bawaannya yaitu reload atau muat ulang halaman
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url ke route penceramah.store
                url: "{{ route('penceramah.store') }}",
                // panggil route kirim
                type: "POST",
                // kirimkan data dari #form_data, otomatis membuat objek atau {}
                data: new FormData(this),
                // aku butuh 3 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
                // prosesData: salah,
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim, hapus validasi error pada formulir dulu
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .input lalu hapus .is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan textnya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika resp.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // parameter key berisi semua nilai attribute name misalnya nama_penceramah
                    // parameter value berisi array yang menyimpan semua pesan error misalnya "Nama Penceramah Harus Diiisi"
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_penceramah_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_penceramah_error lalu isi textnya dengan pesan error dengan cara panggil parameter value, index ke 0
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // jika berhasil menyimpan penceramah
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_tambah index ke 0 lalu atur ulang semua input
                    $("#form_tambah")[0].reset();

                    // reset pratinjau foto
                    // jquery panggil #pratinjau_foto_penceramah, lalu attribute src, value nya di kosongkan pake ""
                    $("#pratinjau_foto_penceramah").attr("src", "");
                    // Nama Penceramah di focuskan
                    // panggil #nama_penceramah lalu focuskan
                    $("#nama_penceramah").focus();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya
                    toastr.success(`Berhasil menyimpan.`);
                };
            });
        });
    </script>
@endpush



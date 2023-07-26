{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Doa')

{{-- kirimkan  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- nama_doa --}}
                <div class="form-group">
                    <label for="nama_doa">Nama Doa<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu nama_doa --}}
                    <input id="nama_doa" name="nama_doa" class="nama_doa_input input form-control" type="text"
                        placeholder="Masukkan Nama Doa" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="nama_doa_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- bacaan_arab --}}
                <div class="form-group">
                    <label for="bacaan_arab">Bacaan Arab<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu bacaan_arab --}}
                    <input id="bacaan_arab" name="bacaan_arab" class="bacaan_arab_input input form-control" type="text"
                        placeholder="Masukkan Bacaan Arab" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="bacaan_arab_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- arti_doanya --}}
                <div class="form-group">
                    <label for="bacaan_latin">Bacaan Latin<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu bacaan_latin --}}
                    <input id="bacaan_latin" name="bacaan_latin" class="bacaan_latin_input input form-control" type="text"
                        placeholder="Masukkan Bacaan Latin" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="bacaan_latin_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- arti_doanya --}}
                <div class="form-group">
                    <label for="arti_doanya">Arti Doanya<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu arti_doanya --}}
                    <input id="arti_doanya" name="arti_doanya" class="arti_doanya_input input form-control" type="text"
                        placeholder="Masukkan Arti Doanya" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="arti_doanya_error pesan_error text-danger"></span>
                </div>

                
                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
                <a href="{{ route('doa.index') }}" class="btn btn-sm btn-danger">
                    <i class="mdi mdi-arrow-left"></i>
                    Kembali
                </a>
            </form>
        </div>
    </div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <script>
        // tampilkan pratinjau gambar ketika user mengubah gambar
        // jika #pilih_gambar_doa diubah maka jalankan fungsi berikut
        $("#pilih_gambar_doa").on("change", function() {
            // ambil gambarnya, this berarti #pilih_gambar_doa, index ke 0
            let gambar = this.files[0];
            // jika ada gambar yang di pilih
            if (gambar) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_gambar_doa lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_gambar_doa").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(gambar);
            };
        });

        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // acara cegah bawaannya yaitu reload
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url ke route doa.store
                url: "{{ route('doa.store') }}",
                // panggil route kirim
                type: "POST",
                // kirimkan data dari #form_data, otomatis membuat objek atau {}
                data: new FormData(this),
                // aku butuh 3 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
                // prosesData: salah,
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim, hapus validasi error dulu
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .input lalu hapus .is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan textnya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapam nya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika resp.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // key berisi semua nilai attribute name misalnya nama_doa, dll.
                    // value berisi array yang menyimpan semua pesan error misalnya Nama Doa Harus Diisi
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_doa_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_doa_error lalu isi textnya dengan pesan error atau value parameter value, index 0
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // lain jika value resp.pesan sama dengan "Doa itu sudah ada" maka
                else if (resp.pesan === "Doa itu sudah ada") {
                    // panggil #nama_doa lalu tambah class is-invalid
                    $("#nama_doa").addClass("is-invalid");
                    // panggil .nama_doa_error lalu text nya diisi value resp.pesan
                    $(".nama_doa_error").text(resp.pesan);
                }
                // jika berhasil menyimpan doa sekali
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_tambah index ke 0 lalu atur ulang semua input
                    $("#form_tambah")[0].reset();
                    // nama doa di focuskan
                    // panggil #nama_doa lalu focuskan
                    $("#nama_doa").focus();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });
    </script>
@endpush

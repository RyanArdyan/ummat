{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Kategori')

{{-- kirimkan  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- nama_kategori --}}
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu nama_kategori --}}
                    <input id="nama_kategori" name="nama_kategori" class="nama_kategori_input input form-control" type="text"
                        placeholder="Masukkan Nama Kategori" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="nama_kategori_error pesan_error text-danger"></span>
                </div>

                
                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
                {{-- panggil route kategori.index --}}
                <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-danger">
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
        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // acara cegah bawaannya yaitu reload
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url ke route kategori.store
                url: "{{ route('kategori.store') }}",
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
                    // key berisi semua nilai attribute name misalnya nama_kategori, dll.
                    // value berisi array yang menyimpan semua pesan error misalnya Nama Kategori Harus Diisi
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_kategori_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_kategori_error lalu isi textnya dengan pesan error atau value parameter value, index 0
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // jika berhasil menyimpan kategori sekali
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_tambah index ke 0 lalu atur ulang semua input
                    $("#form_tambah")[0].reset();
                    // nama Kategori di focuskan
                    // panggil #nama_kategori lalu focuskan
                    $("#nama_kategori").focus();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });
    </script>
@endpush

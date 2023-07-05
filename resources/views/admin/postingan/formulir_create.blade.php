{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'postingan')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')
    <!-- Plugins text editor menggunakan bubble atau quill editor -->
    {{-- asset berarti memanggil folder public --}}
    {{-- cetak panggil folder public/assets --}}
    <link href="{{ asset('adminto/assets/libs/quill/quill.bubble.css') }}" rel="stylesheet"/>
    {{-- agar bisa memilih multipe kategori atau multi select --}}
    <link href="{{ asset('adminto/assets/libs/multiselect/multi-select.css') }}"  rel="stylesheet" />

@endpush

{{-- kirimkan  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- judul_postingan --}}
                <div class="form-group">
                    <label for="judul_postingan">Judul Postingan<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu judul_postingan --}}
                    <input id="judul_postingan" name="judul_postingan" class="judul_postingan_input input form-control" type="text"
                        placeholder="Masukkan Judul Postingan" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="judul_postingan_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="my_multi_select3">Kategori <span class="text-danger"> *</span></label>
                    <select id="my_multi_select3" name="kategori" class="multi-select" multiple="" >
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AS">American Samoa</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                    </select>
                    {{-- pesan error --}}
                    <span class="kategori_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="bubble-editor">Konten<span class="text-danger"> *</span></label>
                    {{-- jangan pernah mengubah #bubble-editor, jika diubah maka akan error --}}
                    <div id="bubble-editor" name="konten_postingan" style="height: 300px;" class="konten_postingan_input input form-control">
                        {{-- <h3>Aku bisa menulis text disini</h3> --}}
                    </div> <!-- end Snow-editor-->
                    <span class="konten_postingan_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_postingan --}}
                <div class="form-group">
                    <label for="pilih_gambar_postingan">Gambar Postingan</label>
                    <br>
                    {{-- asset akan memanggil folder public --}}
                    <img id="pratinjau_gambar_postingan" src=""
                        alt="Gambar postingan" width="150px" height="150px" class="mb-3 rounded">
                    <div class="input-group">
                        <div class="custom-file">
                            <input name="gambar_postingan" type="file" class="input gambar_postingan_input custom-file-input" id="pilih_gambar_postingan">
                            {{-- pesan error --}}
                            <label class="custom-file-label" for="gambar_postingan">Pilih file</label>
                        </div>
                    </div>
                    <span class="pesan_error gambar_postingan_error text-danger"></span>
                </div>

                
                <button id="tombol_simpan" type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
            </form>
        </div>
    </div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <!-- Plugins js untuk text editor  -->
    <script src="{{ asset('adminto/assets/libs/quill/quill.min.js') }}"></script>
    <!-- init js untuk text editor -->
    <script src="{{ asset('adminto/assets/js/pages/form-editor.init.js') }}"></script>

    {{-- Plugin js untuk multi select --}}
    <script src="{{ asset('adminto/assets/libs/multiselect/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('adminto/assets/libs/jquery-quicksearch/jquery.quicksearch.min.js') }}"></script>
    <!-- Init js untuk multi select-->
    <script src="{{ asset('adminto/assets/js/pages/form-advanced.init.js') }}"></script>

    <script>
        // tampilkan pratinjau gambar ketika user mengubah gambar
        // jika #pilih_gambar_postingan diubah maka jalankan fungsi berikut
        $("#pilih_gambar_postingan").on("change", function() {
            // ambil gambarnya, this berarti #pilih_gambar_postingan, index ke 0
            let gambar = this.files[0];
            // jika ada gambar yang di pilih
            if (gambar) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_gambar_postingan lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_gambar_postingan").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(gambar);
            };
        });

        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaannya yaitu reload
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url ke route postingan.store
                url: "{{ route('postingan.store') }}",
                // panggil route kirim
                type: "POST",
                // kirimkan data dari #form_data, otomatis membuat objek atau {}
                data: new FormData(this),
                // aku butuh 2 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
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
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil 
            .done(function(resp) {
                // jika validasi menemukan error
                // jika resp.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // key berisi semua nilai name.
                    // value berisi array yang menyimpan semua pesan error
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .judul_postingan_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .judul_postingan_error lalu isi textnya dengan pesan error
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // jika berhasil menyimpan postingan
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_tambah index ke 0 lalu atur ulang semua input
                    $("#form_tambah")[0].reset();
                    // reset pratinjau gambar
                    // jquery panggil #pratinjau_gambar_postingan, lalu attribute src, value nya di kosongkan pake ""
                    $("#pratinjau_gambar_postingan").attr("src", "");
                    // Judul Postingan di focuskan
                    // panggil #judul_postingan lalu focuskan
                    $("#judul_postingan").focus();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });
    </script>
@endpush

{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Kegiatan Rutin')

{{-- kirimkan  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_tambah">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- is-invalid --}}
                {{-- nama_kegiatan --}}
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu name --}}
                    <input id="nama_kegiatan" name="nama_kegiatan" class="nama_kegiatan_input input form-control" type="text"
                        placeholder="Masukkan Nama Kegiatan" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="nama_kegiatan_error pesan_error text-danger"></span>
                </div>

                {{-- hari --}}
                <div class="form-group">
                    <label for="hari">Hari<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu hari --}}
                    <select name="hari" class="form-control">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value='Jum"at'>Jum'at</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                {{-- is-invalid --}}
                {{-- jam_mulai --}}
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai<span class="text-danger"> *</span></label>
                    <input id="jam_mulai" name="jam_mulai" class="jam_mulai_input input form-control" type="time" style="width: 130px">
                    {{-- pesan error --}}
                    <span class="jam_mulai_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- jam_selesai --}}
                <div class="form-group">
                    <label for="jam_selesai">Jam Selesai<span class="text-danger"> *</span></label>
                    <input id="jam_selesai" name="jam_selesai" class="jam_selesai_input input form-control" type="time" style="width: 130px">
                    {{-- pesan error --}}
                    <span class="jam_selesai_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_kegiatan --}}
                <div class="form-group">
                    <label for="pilih_gambar_kegiatan">Gambar Kegiatan</label>
                    <br>
                    {{-- asset akan memanggil folder public --}}
                    <img id="pratinjau_gambar_kegiatan" src=""
                        alt="Gambar Kegiatan" width="150px" height="150px" class="mb-3 rounded">
                    <div class="input-group">
                        <div class="custom-file">
                            <input name="gambar_kegiatan" type="file" class="input gambar_kegiatan_input custom-file-input" id="pilih_gambar_kegiatan">
                            {{-- pesan error --}}
                            <label class="custom-file-label" for="gambar_kegiatan">Pilih file</label>
                        </div>
                    </div>
                    <span class="pesan_error gambar_kegiatan_error text-danger"></span>
                </div>

                
                <button id="tombol_simpan" type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Simpan
                </button>
            </form>
        </div>
    </div>
@endsection

{{-- dorong vaue @dorong('script') ke @stack('script') --}}
@push('script')
    <script>
        // tampilkan pratinjau gambar ketika user mengubah gambar
        // jika #pilih_gambar_kegiatan diubah maka jalankan fungsi berikut
        $("#pilih_gambar_kegiatan").on("change", function() {
            // ambil gambarnya, this berarti #pilih_gambar_kegiatan, index ke 0
            let gambar = this.files[0];
            // jika ada gambar yang di pilih
            if (gambar) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_gambar_kegiatan lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_gambar_kegiatan").attr("src", e.target.result);
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
                // url ke route kegiatan_rutin.store
                url: "{{ route('kegiatan_rutin.store') }}",
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
                        // contohnya panggil .nama_kegiatan_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_kegiatan_error lalu isi textnya dengan pesan error
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // jika berhasil menyimpan kegiatan rutin
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // // reset formulir
                    // panggil #form_tambah index ke 0 lalu atur ulang semua input termasuk select
                    $("#form_tambah")[0].reset();
                    // reset pratinjau gambar
                    // jquery panggil #pratinjau_gambar_kegiatan, lalu attribute src, value nya di kosongkan pake ""
                    $("#pratinjau_gambar_kegiatan").attr("src", "");
                    // nama kegiatan di focuskan
                    // panggil #nama_kegiatan lalu focuskan
                    $("#nama_kegiatan").focus();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });
    </script>
@endpush

{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Edit Postingan')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')
    {{-- untuk menggunakan trix editor --}}
    {{-- cetak panggil asset('') berarti memanggil folder public --}}
    <link rel="stylesheet" href="{{ asset('trix_editor/css/trix_2.0.0.css') }}">

    <style>
        /* Menyembunyikan tombol upload file di trix editor */
        /* panggil .berikut */
        trix-toolbar .trix-button-group--file-tools {
            /* tampilan: tidak ada */
            display: none;
        }
    </style>
@endpush

{{-- kirimkan value @bagian('konten') ke @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_edit">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- tidak ada method="PUT" jadi aku paksa panggil route tipe PUT --}}
                @method('PUT')
                {{-- postingan_id --}}
                <div class="form-group" hidden>
                    <label for="postingan_id">Postingan ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_postingan, column postingan_id yang di kirimkan postinganController, method edit di attribute value --}}
                    <input id="postingan_id" name="postingan_id" class="form-control" type="text" readonly value="{{ $detail_postingan->postingan_id }}">
                </div>

                {{-- is-invalid --}}
                {{-- judul_postingan --}}
                <div class="form-group">
                    <label for="judul_postingan">Judul Postingan<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_postingan, column judul_postingan yang di kirimkan postinganController, method edit di attribute value --}}
                    <input id="judul_postingan" name="judul_postingan" class="judul_postingan_input input form-control" type="text"
                        placeholder="Masukkan Judul Postingan" autocomplete="off" value="{{ $detail_postingan->judul_postingan }}">
                    {{-- pesan error --}}
                    <span class="judul_postingan_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori <span class="text-danger"> *</span></label>
                    <br>
                    <p>Kategori-kategori yang anda pilih sebelumnya:
                        {{-- Lakukan pengulangan terhadap value variable $kategori_terpilih sebagai $k_terpilih, jadi $k_terpilih berisi setiap nilai kategori_terpilih --}}
                        @foreach ($kategori_terpilih as $k_terpilih)
                            <span class="text-warning">
                                {{-- cetak setiap value $_terpilih, column nama_kategori --}}
                                {{ $k_terpilih->nama_kategori }}, 
                            </span>
                        @endforeach
                    </p>
                    <select id="kategori_id" name="kategori_id[]" multiple="" class="form-control input kategori_id_input" style="width: 30%">
                        {{-- Jika ingin element <option> terpilih maka gunakan attribute selected --}}
                        {{-- lakukan pengulangan element option menggunakan pengulangan @untukSetiap, value attribute value berisi setiap value column kategori_id, value element option berisi setiap value column nama_kategori --}}
                        @foreach($semua_kategori as $kategori)
                            <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <br>
                    {{-- keterangan --}}
                    <span class="text-warning">Tekan CTRL + Click agar bisa pilih ganda.</span>
                    <br>
                    {{-- pesan error --}}
                    <span class="kategori_id_error pesan_error text-danger"></span>
                </div>

                <div class="form-group">
                    <label for="konten_postingan">Konten<span class="text-danger"> *</span></label>
                    {{-- {!!  !!} berarti dia akan mencetak tag html, jika user menulis <h1>fsfa</h1> maka <h1></h1> juga akan tercetak --}}
                    <input id="konten_postingan" type="hidden" name="konten_postingan" value="{!! $detail_postingan->konten_postingan !!}" />
                    {{-- pake package trix editor, jangan mengubah .trix-content, biarkan default --}}
                    <trix-editor input="konten_postingan" class="trix-content"></trix-editor>
                    <span class="konten_postingan_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_postingan --}}
                <div class="form-group">
                    <label for="pilih_gambar_postingan">Gambar postingan</label>
                    <br>
                    {{-- / berarti public arahkan ke storage/gambar_postingan/ lalu panggil value $detail_postingan, column gambar_postingan --}}
                    <img id="pratinjau_gambar_postingan" src="/storage/gambar_postingan/{{ $detail_postingan->gambar_postingan }}"
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

                <div class="form-group">
                    <label for="dipublikasi_pada">Dipublikasi Pada</label>
                    {{-- cetak value $detail_postigan, column dipublikasi_pada --}}
                    <input value="{{ $detail_postingan->dipublikasi_pada }}" type="datetime-local" id="dipublikasi_pada" name="dipublikasi_pada" class="form-control" style="width: 200px">
                    <span class="pesan_error dipublikasi_pada_error text-danger"></span>
                </div>
                
                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Perbarui
                </button>
                {{-- panggil route postingan.index --}}
                <a href="{{ route('postingan.index') }}" class="btn btn-sm btn-danger">
                    <i class="mdi mdi-arrow-left"></i>
                    Kembali
                </a>
            </form>
        </div>
    </div>
@endsection

{{-- dorong vaue @dorong('script') ke @stack('script') --}}
@push('script')
    {{-- Untuk mengggunakan trix editor --}}
    {{-- cetak asset('') berarti panggil folder public --}}
    <script src="{{ asset('trix_editor/js/trix_2.0.0.umd.min.js') }}"></script>

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

        
        // Update
        // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event atau acara cegah bawaan nya yaitu reload atau muat ulang
            e.preventDefault();
            // berisi panggil #postingan_id lalu ambil value nya
            let postingan_id = $("#postingan_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url /postingan/ lalu kirimkan value variable postingan_id
                url: `/postingan/${postingan_id}`,
                // panggil route tipe PUT karena sudah aku paksa ubah di formulir edit
                type: "POST",
                // kirimkan formulir data atau value input2x dari #form_edit
                data: new FormData(this),
                // aku butuh ketiga baris kode di bawah ini
                processData: false,
                contentType: false,
                cache: false,
                // hapus validasi error sebelum formulir di kirim
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .input lalu hapus class is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan text nya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tangggapannya
            .done(function(resp) {
                console.log(resp);


                // jika validasi error
                // jika tangggapan.status sama dengan 0
                if (resp.status === 0) {
                    // tampilkan validasi error
                    // lakukan pengulangan kepada resp.error
                    // key berisi semua value attribute name yang error misalnya judul_postingan, value berisi pesan error nya misalnya "Nama postingan harus diisi"
                    // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .judul_postingan_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil .judul_postingan_error lalu isi textnya dengan paramter value
                        $(`.${key}_error`).text(value);
                    });
                    // jika validasi berhasil
                } else if (resp.status === 200) {
                    // berikan notifikasi menggunakan package sweetalert
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Berhasil menyimpan perubahan',
                        icon: 'success'
                    });
                    // setelah 2 detik 500 milidetik maka jalankan fungsi berikt
                    setTimeout(function() {
                        // panggil route postingan_.index
                        window.location.href = "{{ route('postingan.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

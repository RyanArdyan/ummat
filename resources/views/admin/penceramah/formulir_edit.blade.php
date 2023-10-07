{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian('nama') ke parent nya yaitu admin.layouts.app --}}
@section('title', 'Edit penceramah')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')

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
                {{-- penceramah_id --}}
                <div class="form-group" hidden>
                    <label for="penceramah_id">penceramah ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_penceramah, column penceramah_id yang di kirimkan penceramahController, method edit di attribute value --}}
                    <input id="penceramah_id" name="penceramah_id" class="form-control" type="text" readonly value="{{ $detail_penceramah->penceramah_id }}">
                </div>

                {{-- is-invalid --}}
                {{-- nama_penceramah --}}
                <div class="form-group">
                    <label for="nama_penceramah">Nama Penceramah<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_penceramah, column nama_penceramah yang di kirimkan penceramahController, method edit di attribute value --}}
                    <input id="nama_penceramah" name="nama_penceramah" class="nama_penceramah_input input form-control" type="text"
                        placeholder="Masukkan Nama Penceramah" autocomplete="off" value="{{ $detail_penceramah->nama_penceramah }}">
                    {{-- pesan error --}}
                    <span class="nama_penceramah_error pesan_error text-danger"></span>
                </div>

                {{-- foto_penceramah --}}
                <div class="form-group">
                    <label for="pilih_foto_penceramah">Foto penceramah</label>
                    <br>
                    {{-- / berarti panggil folder public arahkan ke storage/foto_penceramah/ lalu panggil value $detail_penceramah, column foto_penceramah --}}
                    <img id="pratinjau_foto_penceramah" src="/storage/foto_penceramah/{{ $detail_penceramah->foto_penceramah }}"
                        alt="Foto penceramah" width="150px" height="150px" class="mb-3 rounded">
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
                    Perbarui
                </button>
                {{-- panggil route admin.penceramah.index --}}
                <a href="{{ route('admin.penceramah.index') }}" class="btn btn-sm btn-danger">
                    <i class="mdi mdi-arrow-left"></i>
                    Kembali
                </a>
            </form>
        </div>
    </div>
@endsection

{{-- dorong vaue @dorong('script') ke @stack('script') --}}
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
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_foto_penceramah lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_foto_penceramah").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(foto);
            };
        });

        
        // Update
        // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event atau acara cegah bawaan nya yaitu reload atau muat ulang
            e.preventDefault();
            // berisi panggil #penceramah_id lalu ambil value nya
            let penceramah_id = $("#penceramah_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url /admin/penceramah/ lalu kirimkan value variable penceramah_id
                url: `/admin/penceramah/${penceramah_id}`,
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
                // jika validasi error
                // jika tangggapan.status sama dengan 0
                if (resp.status === 0) {
                    // tampilkan validasi error
                    // lakukan pengulangan kepada resp.error
                    // key berisi semua value attribute name yang error misalnya nama_penceramah, value berisi pesan error nya misalnya "Nama Penceramah harus diisi"
                    // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_penceramah_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil .nama_penceramah_error lalu isi textnya dengan paramter value
                        $(`.${key}_error`).text(value);
                    });
                } 
                // jika validasi berhasil
                else if (resp.status === 200) {
                    // berikan notifikasi menggunakan package sweetalert
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Berhasil menyimpan perubahan',
                        icon: 'success'
                    });
                    // setelah 2 detik 500 milidetik maka jalankan fungsi berikt
                    setTimeout(function() {
                        // panggil route penceramah_.index
                        window.location.href = "{{ route('admin.penceramah.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

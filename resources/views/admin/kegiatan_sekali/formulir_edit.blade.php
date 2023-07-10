{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Edit Kegiatan Sekali')

{{-- kirimkan value @bagian('konten') ke @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_edit">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- tidak ada method="PUT" jadi laravel mamaksa panggil route tipe PUT --}}
                @method('PUT')
                {{-- kegiatan_sekali_id --}}
                <div class="form-group" hidden>
                    <label for="kegiatan_sekali_id">Kegiatan Sekali ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan_sekali, column kegiatan_sekali_id yang di kirimkan KegiatanSekaliController, method edit di attribute value --}}
                    <input id="kegiatan_sekali_id" name="kegiatan_sekali_id" class="form-control" type="text" readonly value="{{ $detail_kegiatan_sekali->kegiatan_sekali_id }}">
                </div>


                {{-- is-invalid --}}
                {{-- nama_kegiatan --}}
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan_sekali, column nama_kegiatan yang di kirimkan KegiatanSekaliController, method edit di attribute value --}}
                    <input id="nama_kegiatan" name="nama_kegiatan" class="nama_kegiatan_input input form-control" type="text"
                        placeholder="Masukkan Nama Kegiatan" autocomplete="off" value="{{ $detail_kegiatan_sekali->nama_kegiatan }}">
                    {{-- pesan error --}}
                    <span class="nama_kegiatan_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- tanggal --}}
                <div class="form-group">
                    <label for="tanggal">Tanggal<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan_sekali, column tanggal yang di kirimkan KegiatanSekaliController, method edit di attribute value --}}
                    <input id="tanggal" name="tanggal" class="tanggal_input input form-control" type="date" style="width: 150px" 
                    value="{{ $detail_kegiatan_sekali->tanggal }}">
                    {{-- pesan error --}}
                    <span class="tanggal_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- jam_mulai --}}
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan_sekali, column jam_mulai yang di kirimkan KegiatanSekaliController, method edit di attribute value --}}
                    <input id="jam_mulai" name="jam_mulai" class="jam_mulai_input input form-control" type="time" style="width: 130px" value="{{ $detail_kegiatan_sekali->jam_mulai }}">
                    {{-- pesan error --}}
                    <span class="jam_mulai_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- jam_selesai --}}
                <div class="form-group">
                    <label for="jam_selesai">Jam Selesai<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan_sekali, column jam_selesai yang di kirimkan KegiatanSekaliController, method edit di attribute value --}}
                    <input id="jam_selesai" name="jam_selesai" class="jam_selesai_input input form-control" type="time" style="width: 130px" value="{{ $detail_kegiatan_sekali->jam_selesai }}">
                    {{-- pesan error --}}
                    <span class="jam_selesai_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_kegiatan --}}
                <div class="form-group">
                    <label for="pilih_gambar_kegiatan">Gambar Kegiatan</label>
                    <br>
                    {{-- / berarti public arahkan ke storage/gambar_kegiatan_sekali/ lalu panggil value $detail_kegiatan_sekali, column gambar_kegiatan --}}
                    <img id="pratinjau_gambar_kegiatan" src="/storage/gambar_kegiatan_sekali/{{ $detail_kegiatan_sekali->gambar_kegiatan }}"
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
                    Perbarui
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

        
        // Update
        // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event atau acara cegah bawaan nya yaitu reload atau muat ulang
            e.preventDefault();
            // berisi panggil #kegiatan_sekali_id lalu ambil value nya
            let kegiatan_sekali_id = $("#kegiatan_sekali_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url /kegiatan-sekali/ lalu kirimkan kegiatan_sekali_id
                url: `/kegiatan-sekali/${kegiatan_sekali_id}`,
                // panggil route tipe PUT karena sudah aku paksa ubah di modal edit
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
                    // key berisi semua value attribute name yang error misalnya nama_kegiatan, value berisi pesan error nya misalnya "Nama kegiatan harus diisi"
                    // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_kegiatan_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil .nama_kegiatan_error lalu isi textnya dengan paramter value
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
                        // panggil route kegiatan_sekali.index
                        window.location.href = "{{ route('kegiatan_sekali.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

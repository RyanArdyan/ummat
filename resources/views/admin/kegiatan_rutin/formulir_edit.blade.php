{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Edit Kegiatan Rutin')

{{-- kirimkan value @bagian('konten') ke @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_edit">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- tidak ada method="PUT" jadi aku paksa panggil route tipe PUT --}}
                @method('PUT')
                {{-- kegiatan_rutin_id --}}
                <div class="form-group" hidden>
                    <label for="kegiatan_rutin_id">Kegiatan Rutin ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan, column kegiatan_rutin_id yang di kirimkan KegiatanRutinController, method edit di attribute value --}}
                    <input id="kegiatan_rutin_id" name="kegiatan_rutin_id" class="form-control" type="text" readonly value="{{ $detail_kegiatan->kegiatan_rutin_id }}">
                </div>


                {{-- is-invalid --}}
                {{-- nama_kegiatan --}}
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan, column nama_kegiatan yang di kirimkan KegiatanRutinController, method edit di attribute value --}}
                    <input id="nama_kegiatan" name="nama_kegiatan" class="nama_kegiatan_input input form-control" type="text"
                        placeholder="Masukkan Nama Kegiatan" autocomplete="off" value="{{ $detail_kegiatan->nama_kegiatan }}">
                    {{-- pesan error --}}
                    <span class="nama_kegiatan_error pesan_error text-danger"></span>
                </div>

                {{-- hari --}}
                <div class="form-group">
                    <label for="hari">Hari<span class="text-danger"> *</span></label>
                    {{-- value input akan masuk ke value atttribute name yaitu hari --}}
                    <select name="hari" class="form-control">
                        {{-- Jika value detail_kegiatan_rutin, column hari sama dengan "Senin" maka cetak attribute selected kalau bukan maka kasi string kosong --}}
                        <option {{ ($detail_kegiatan->hari === 'Senin') ? 'selected' : '' }} value="Senin">Senin</option>
                        <option {{ ($detail_kegiatan->hari === 'Selasa') ? 'selected' : '' }} value="Selasa">Selasa</option>
                        <option {{ ($detail_kegiatan->hari === 'Rabu') ? 'selected' : '' }} value="Rabu">Rabu</option>
                        <option {{ ($detail_kegiatan->hari === 'Kamis') ? 'selected' : '' }} value="Kamis">Kamis</option>
                        <option {{ ($detail_kegiatan->hari === 'Jum"at') ? 'selected' : '' }} value='Jum"at'>Jum'at</option>
                        <option {{ ($detail_kegiatan->hari === 'Sabtu') ? 'selected' : '' }} value="Sabtu">Sabtu</option>
                        <option {{ ($detail_kegiatan->hari === 'Minggu') ? 'selected' : '' }} value="Minggu">Minggu</option>
                    </select>
                </div>

                {{-- is-invalid --}}
                {{-- jam_mulai --}}
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan, column jam_mulai yang di kirimkan KegiatanRutinController, method edit di attribute value --}}
                    <input id="jam_mulai" name="jam_mulai" class="jam_mulai_input input form-control" type="time" style="width: 160px" value="{{ $detail_kegiatan->jam_mulai }}">
                    {{-- pesan error --}}
                    <span class="jam_mulai_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- jam_selesai --}}
                <div class="form-group">
                    <label for="jam_selesai">Jam Selesai<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kegiatan, column jam_selesai yang di kirimkan KegiatanRutinController, method edit di attribute value --}}
                    <input id="jam_selesai" name="jam_selesai" class="jam_selesai_input input form-control" type="time" style="width: 160px" value="{{ $detail_kegiatan->jam_selesai }}">
                    {{-- pesan error --}}
                    <span class="jam_selesai_error pesan_error text-danger"></span>
                </div>

                {{-- gambar_kegiatan --}}
                <div class="form-group">
                    <label for="pilih_gambar_kegiatan">Gambar Kegiatan</label>
                    <br>
                    {{-- / berarti public arahkan ke storage/gambar_kegiatan/ lalu panggil value $detail_kegiatan, column gambar_kegiatan --}}
                    <img id="pratinjau_gambar_kegiatan" src="/storage/gambar_kegiatan_rutin/{{ $detail_kegiatan->gambar_kegiatan }}"
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
                
                <button id="tombol_simpan" type="submit" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-content-save"></i>
                    Perbarui
                </button>
                {{-- panggil route admin.kegiatan_rutin.index --}}
                <a href="{{ route('admin.kegiatan_rutin.index') }}" class="btn btn-sm btn-danger">
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
            // berisi panggil #kegiatan_rutin_id lalu ambil value nya
            let kegiatan_rutin_id = $("#kegiatan_rutin_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url berikut lalu kirimkan kegiatan_rutin_id
                url: `/admin/kegiatan-rutin/${kegiatan_rutin_id}`,
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
                } 
                // jika validasi berhasil
                // lain jika value tanggapan.status sama dengan 200
                else if (resp.status === 200) {
                    // berikan notifikasi menggunakan package sweetalert
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Berhasil menyimpan perubahan',
                        icon: 'success'
                    });
                    // setelah 2 detik 500 milidetik maka jalankan fungsi berikt
                    setTimeout(function() {
                        // panggil route admin.kegiatan_rutin.index
                        window.location.href = "{{ route('admin.kegiatan_rutin.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

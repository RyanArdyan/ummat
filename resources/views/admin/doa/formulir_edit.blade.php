{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Edit Doa')

{{-- kirimkan value @bagian('konten') ke @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_edit">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- tidak ada method="PUT" jadi laravel mamaksa panggil route tipe PUT --}}
                @method('PUT')
                {{-- doa_id --}}
                <div class="form-group" hidden>
                    <label for="doa_id">Doa ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_doa, column doa_id yang di kirimkan DoaController, method edit di attribute value --}}
                    <input id="doa_id" name="doa_id" class="form-control" type="text" readonly value="{{ $detail_doa->doa_id }}">
                </div>

                {{-- is-invalid --}}
                {{-- nama_doa --}}
                <div class="form-group">
                    <label for="nama_doa">Nama doa<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_doa, column nama_doa yang di kirimkan DoaController, method edit di attribute value --}}
                    <input id="nama_doa" name="nama_doa" class="nama_doa_input input form-control" type="text"
                        placeholder="Edit Nama doa" autocomplete="off" value="{{ $detail_doa->nama_doa }}">
                    {{-- pesan error --}}
                    <span class="nama_doa_error pesan_error text-danger"></span>
                </div>


                {{-- is-invalid --}}
                {{-- bacaan_arab --}}
                <div class="form-group">
                    <label for="bacaan_arab">Bacaan Arab<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_doa, column bacaan_arab yang di kirimkan DoaController, method edit di attribute value --}}
                    <input id="bacaan_arab" name="bacaan_arab" class="bacaan_arab_input input form-control" type="text"
                        placeholder="Edit Bacaan Arab" autocomplete="off" value="{{ $detail_doa->bacaan_arab }}">
                    {{-- pesan error --}}
                    <span class="bacaan_arab_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- bacaan_latin --}}
                <div class="form-group">
                    <label for="bacaan_latin">Bacaan Latin<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_doa, column bacaan_latin yang di kirimkan DoaController, method edit di attribute value --}}
                    <input id="bacaan_latin" name="bacaan_latin" class="bacaan_latin_input input form-control" type="text"
                        placeholder="Edit Bacaan Latin" autocomplete="off" value="{{ $detail_doa->bacaan_latin }}">
                    {{-- pesan error --}}
                    <span class="bacaan_latin_error pesan_error text-danger"></span>
                </div>

                {{-- is-invalid --}}
                {{-- arti_doanya --}}
                <div class="form-group">
                    <label for="arti_doanya">Arti Doanya<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_doa, column arti_doanya yang di kirimkan DoaController, method edit di attribute value --}}
                    <input id="arti_doanya" name="arti_doanya" class="arti_doanya_input input form-control" type="text"
                        placeholder="Edit Arti Doanya" autocomplete="off" value="{{ $detail_doa->arti_doanya }}">
                    {{-- pesan error --}}
                    <span class="arti_doanya_error pesan_error text-danger"></span>
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
        // Update
        // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event atau acara cegah bawaan nya yaitu reload atau muat ulang
            e.preventDefault();
            // berisi panggil #doa_id lalu ambil value nya
            let doa_id = $("#doa_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url /doa/ lalu kirimkan value variable doa_id
                url: `/doa/${doa_id}`,
                // panggil route tipe PUT karena sudah aku paksa ubah di modal edit
                type: "POST",
                // kirimkan formulir data atau value input2x dari #form_edit
                // data: baru FormData(ini)
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
                    // key berisi semua value attribute name yang error misalnya nama_doa, value berisi pesan error nya misalnya "Nama doa harus diisi"
                    // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_doa_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil .nama_doa_error lalu isi textnya dengan paramter value misalnya nama doa harus diisi
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
                        // panggil route doa.index
                        window.location.href = "{{ route('doa.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

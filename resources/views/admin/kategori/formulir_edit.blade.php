{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Edit kategori')

{{-- kirimkan value @bagian('konten') ke @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <form id="form_edit">
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                @csrf
                {{-- tidak ada method="PUT" jadi laravel mamaksa panggil route tipe PUT --}}
                @method('PUT')
                {{-- kategori_id --}}
                <div class="form-group" hidden>
                    <label for="kategori_id">kategori ID<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kategori, column kategori_id yang di kirimkan kategoriController, method edit di attribute value --}}
                    <input id="kategori_id" name="kategori_id" class="form-control" type="text" readonly value="{{ $detail_kategori->kategori_id }}">
                </div>

                {{-- is-invalid --}}
                {{-- nama_kategori --}}
                <div class="form-group">
                    <label for="nama_kategori">Nama kategori<span class="text-danger"> *</span></label>
                    {{-- cetak value $detail_kategori, column nama_kategori yang di kirimkan kategoriController, method edit di attribute value --}}
                    <input id="nama_kategori" name="nama_kategori" class="nama_kategori_input input form-control" type="text"
                        placeholder="Edit Nama kategori" autocomplete="off" value="{{ $detail_kategori->nama_kategori }}">
                    {{-- pesan error --}}
                    <span class="nama_kategori_error pesan_error text-danger"></span>
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
            // berisi panggil #kategori_id lalu ambil value nya
            let kategori_id = $("#kategori_id").val();
            // jquery lakukan ajax
            $.ajax({
                // ke method update
                // panggil url /kategori/ yang tipe nya PUT lalu kirimkan value variable kategori_id
                url: `/kategori/${kategori_id}`,
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
                    // key berisi semua value attribute name yang error misalnya nama_kategori, value berisi pesan error nya misalnya "Nama kategori harus diisi"
                    // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_kategori_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil .nama_kategori_error lalu isi textnya dengan paramter value misalnya nama kategori harus diisi
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
                    // setelah 2 detik 500 milidetik maka jalankan fungsi berikut
                    setTimeout(function() {
                        // panggil route kategori.index
                        window.location.href = "{{ route('kategori.index') }}";
                    }, 2500);
                };
            });
        });
    </script>
@endpush

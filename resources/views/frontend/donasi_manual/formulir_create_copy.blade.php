{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Formulir Donasi')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')
    
    <div class="container">
        <h1 class="mt-4 text-center">Formulir Donasi</h1>

        <form id="form_tambah">
            {{-- laravel mewajibkan keamanan dari serangan csrf --}}
            @csrf
            {{-- is-invalid --}}
            {{-- jumlah_donasi --}}
            <div class="form-group mb-3">
                <label for="jumlah_donasi">Jumlah donasi<span class="text-danger"> *</span></label>
                {{-- value input akan masuk ke value atttribute name yaitu jumlah_donasi --}}
                {{-- aku pake package inputmask --}}
                {{-- ketika aku ketik maka panggil fungsi number lalu kirimkan event nya --}}
                <input onkeypress="return number(event)"
                    data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','"
                    id="jumlah_donasi" name="jumlah_donasi" class="jumlah_donasi_input input_mask input form-control"
                    type="text" placeholder="Masukkan Jumlah Donasi" autocomplete="off">
                {{-- pesan error --}}
                <span class="jumlah_donasi_error pesan_error text-danger"></span>
            </div>

            {{-- is-invalid --}}
            {{-- nomor_wa --}}
            <div class="form-group mb-3">
                <label for="nomor_wa">Nomor WA<span class="text-danger"> *</span></label>
                {{-- value input akan masuk ke value atttribute name yaitu nomor_wa --}}
                <input value="{{ $nomor_wa_user }}" id="nomor_hp" name="nomor_wa" class="nomor_wa_input input form-control"
                    type="number" placeholder="Masukkan Nomor WA" autocomplete="off">
                {{-- pesan error --}}
                <span class="nomor_wa_error pesan_error text-danger"></span>
            </div>

            {{-- is-invalid --}}
            {{-- pesan --}}
            <div class="form-group mb-3">
                <label for="pesan_donasi">Pesan Donasi<span class="text-danger"> *</span></label>
                {{-- value input akan masuk ke value atttribute name yaitu pesan_donasi --}}
                <input id="pesan_donasi" name="pesan_donasi" class="pesan_donasi_input input form-control" type="text"
                    placeholder="Pesan kepada pengelola donasi" autocomplete="off">
                {{-- pesan_donasi error --}}
                <span class="pesan_donasi_error pesan_error text-danger"></span>
            </div>

            <button id="tombol_donasi" type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i>
                Donasi
            </button>
            {{-- cetak panggil route admin.donasi.index --}}
            <a href="{{ route('admin.donasi.index') }}" class="btn btn-danger">
                <i class="mdi mdi-arrow-left">
                    Kembali
                </i>
            </a>
                
            <div class="mb-5"></div>
        </form>
    </div>
@endsection


{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
     {{-- input mask agar bisa mengubah 1000 menjadi Rp 1.000 --}}
    {{-- cetak, asset('') otomatis memanggil folder public --}}
    <script src="{{ asset('inputmask_5') }}/dist/jquery.inputmask.js"></script>
    <script src="{{ asset('inputmask_5') }}/dist/bindings/inputmask.binding.js"></script>

    <script>
        // Ini berarti jika form nya dikirim maka hapus input mask nya, contoh Rp 1.000 akan menjadi 1000
        // panggil .input_mask lalu panggil fungsi inputtpeng
        $(".input_mask").inputmask();

        // jika formulir tambah dikirim
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaannya yaitu reload
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url ke route donasi.store
                url: "{{ route('donasi.store') }}",
                // panggil route tipe kirim
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
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika resp.status sama dengan 0
                if (resp.status === 0) {
                    // cetak value dari tanggapan.kesalahan
                    // console.log(resp.errors);

                    // lakukan pengulangan
                    // key berisi semua nilai attribute name misalnya jumlah_donasi
                    // value berisi array yang menyimpan semua pesan error misalnya "Judul donasi Harus Diiisi"
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai))
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .jumlah_donasi_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .jumlah_donasi_error lalu isi textnya dengan pesan error
                        $(`.${key}_error`).text(value[0]);

                    });
                }
                // jika berhasil menyimpan donasi
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // alert("payment success!");
                    // panggil url /donasi/faktur/ lalu kirimkan value resp.donasi_id dan kirimkan value resp.snapToken
                    window.location.href = `/donasi/faktur/${resp.donasi_id}/${resp.snapToken}`;
                };
            });
        });
    </script>
@endpush

{{-- kalau berhasil harus nya dia mencetak alert('oke') --}}

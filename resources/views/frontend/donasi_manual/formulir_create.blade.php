{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Formulir Donasi Manual')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')
    
    <div class="container">
        <h1 class="mt-4 text-center">Formulir Donasi</h1>

        {{-- cetak panggil route donasi_manual.store --}}
        <form action="{{ route('donasi_manual.store') }}" method="POST" enctype="multipart/form-data">
            {{-- laravel mewajibkan keamanan dari serangan csrf --}}
            @csrf
            {{-- method nya adalah POST --}} 
            @method('POST')
            {{-- is-invalid --}}
            {{-- jumlah_donasi --}}
            <div class="form-group mb-3">
                <label for="jumlah_donasi">Jumlah donasi<span class="text-danger"> *</span></label>
                {{-- aku pake package inputmask agar 1000 menjadi Rp 1.000 --}}
                {{-- ketika aku ketik maka panggil fungsi number lalu kirimkan event nya --}}
                <input onkeypress="return number(event)"
                    data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','"
                    id="jumlah_donasi" name="jumlah_donasi" class="input_mask form-control @error('jumlah_donasi') is-invalid @enderror"
                    type="text" placeholder="Masukkan Jumlah Donasi" autocomplete="off">
                {{-- pesan error, jika jumlah_donasi error maka buat element span --}}
                @error('jumlah_donasi')
                    {{-- cetak value variable $message --}}
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- is-invalid --}}
            {{-- nomor_wa --}}
            <div class="form-group mb-3">
                <label for="nomor_wa">Nomor WA<span class="text-danger"> *</span></label>
                {{-- attribute value cetak value variable $nomor_wa_user --}}
                {{-- Jika value input name="nomor_wa" error maka cetak class is-invalid --}}
                <input value="{{ $nomor_wa_user }}" id="nomor_hp" name="nomor_wa" class="@error('nomor_wa') is-invalid @enderror form-control"
                    type="number" placeholder="Masukkan Nomor WA" autocomplete="off">
                {{-- pesan error, jika value input name="nomor_wa" error maka buat elemeent span --}}
                @error('nomor_wa')
                    {{-- cetak value variable $message --}}
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- is-invalid --}}
            {{-- foto_bukti --}}
            <div class="form-group mb-3">
                <label for="foto_bukti">Foto Bukti<span class="text-danger"> *</span></label>
                <br class="mb-3">
                <img id="pratinjau_gambar_postingan" src="" alt="Gambar postingan" width="150px" height="150px" class="mb-3 rounded">
                {{-- attribute value cetak value variable $foto_bukti_user --}}
                {{-- Jika value input name="foto_bukti" error maka cetak class is-invalid --}}
                <input id="pilih_gambar_postingan" name="foto_bukti" class="@error('foto_bukti') is-invalid @enderror form-control"
                    type="file" autocomplete="off">
                {{-- pesan error, jika value input name="foto_bukti" error maka buat elemeent span --}}
                @error('foto_bukti')
                    {{-- cetak value variable $message --}}
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- is-invalid --}}
            {{-- Tipe Pembayaran --}}
            <div class="form-group mb-3">
                <label for="tipe_pembayaran">Tipe Pembayaran<span class="text-danger"> *</span></label>
                <select name="tipe_pembayaran" class="form-select">
                    <option selected value="Gopay">Gopay</option>
                    <option value="Bank BNI">Bank BNI</option>
                </select>
            </div>

            {{-- is-invalid --}}
            {{-- pesan --}}
            <div class="form-group mb-3">
                <label for="pesan_donasi">Pesan Donasi<span class="text-danger"> *</span></label>
                {{-- value input akan masuk ke value atttribute name yaitu pesan_donasi --}}
                <input id="pesan_donasi" name="pesan_donasi" class="@error('pesan_donasi') is-invalid @enderror pesan_donasi_input input form-control" type="text"
                    placeholder="Pesan kepada pengelola donasi" autocomplete="off">
                {{-- pesan error, jika pesan_donasi error maka buat elemeent span --}}
                @error('pesan_donasi')
                    {{-- cetak value variable $message --}}
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button id="tombol_donasi" type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i>
                Donasi
            </button>
            {{-- cetak panggil route frontend.index --}}
            <a href="{{ route('frontend.index') }}" class="btn btn-danger">
                <i class="mdi mdi-arrow-left">
                    Daftar Pendonasi
                </i>
            </a>
                
            <div class="mb-5"></div>
        </form>
    </div>
@endsection


{{-- dorong value @dorong('script') ke @stack('script') milik parent nya --}}
@push('script')
     {{-- input mask agar bisa mengubah 1000 menjadi Rp 1.000 --}}
    {{-- cetak, asset('') otomatis memanggil folder public --}}
    <script src="{{ asset('inputmask_5') }}/dist/jquery.inputmask.js"></script>
    <script src="{{ asset('inputmask_5') }}/dist/bindings/inputmask.binding.js"></script>

    <script>
        // Ini berarti jika form nya dikirim maka hapus input mask nya, contoh Rp 1.000 akan menjadi 1000
        // panggil .input_mask lalu panggil fungsi input masker 
        $(".input_mask").inputmask();

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
    </script>
@endpush


{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Postingan')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- @bagian('konten') berfungsi mengirimkan value nya ke dalam @yield('konten') --}}
@section('konten')
<div class="row">
    {{-- gunakan script php --}}
    @php
        // gunakan carbon untuk mengubah 2023-06-25 menjadi Sunday, 25 June 2023
        use Carbon\Carbon;
    @endphp

    {{-- looping sebanyak jumlah semua_postingan --}}
    {{-- untukSetiap ($semua_postingan sebagai $postingan) --}}
    @foreach ($semua_postingan as $postingan)
        {{-- panggil route postingan.show, lalu kirimkan data berupa array, key slug_postingan berisi value detail_ppstingan, column slug_postingan --}}
        <a href="{{ route('postingan.show', [$postingan->slug_postingan]) }}">
            {{-- berarti di satu baris, ada 3 column --}}
            <div class="col-sm-4">
                <!-- Simple card -->
                <div class="card">
                    {{-- cetak asset() berarti panggil folder public --}}
                    <img class="card-img-top img-fluid" src='{{ asset("storage/gambar_postingan/uji.jpg") }}' alt="{{ $postingan->judul_postingan }}">
                    <div class="card-body">
                        {{-- cetak setiap detail_postingan, column judul_postingan --}}
                        <h4 class="card-title mb-2">{{ $postingan->judul_postingan }}</h4>

                        {{-- untuk mencetak misalnya postingan ini ditulis 1 hari yang lalu --}}
                        @php 
                            // Untuk mencetak waktu sekarang
                            $waktu_saat_ini = Carbon::now();
                            // untuk mencetak kapan postingan ini dipublikasi_pada
                            $waktu_postingan = Carbon::parse($postingan->dipublikasi_pada);
                        @endphp

                        {{-- Untuk mencetak misalnya postingan ini dibuat 1 hari yang lalu --}}
                        <p class="card-text float-right">{{ $waktu_postingan->diffForHumans($waktu_saat_ini) }}</p>
                        {{-- cetak nama penulis nya --}}
                        {{-- cetak value detail_postingan yang berelasi dengan detail_user, column name --}}
                        <p class="card-text">{{ $postingan->user->name }}</p>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
<script>
    // tambahkan kedua baris kode berikut agar bilah samping nya runtuh atau sidebar collapse atau agar left menu nya menjadi kecil
    // panggil element body, lalu tambahkan class="enlarged"
    $("body").addClass("enlarged");
    // panggil element body, lalu tambah attribute data-tetap-membesar, value nya benar
    $("body").attr("data-keep-enlarged", "true");
</script>
@endpush
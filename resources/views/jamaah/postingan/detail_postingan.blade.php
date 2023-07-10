{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Detail Postingan')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- @bagian('konten') berfungsi mengirimkan value nya ke dalam @yield('konten') --}}
@section('konten')
<div class="row">
    {{-- gunakan script php agar bisa menulis kode php di .blade atau html --}}
    @php
        // gunakan carbon agar bisa menampilkan postingan ini dibuat 1 hari yanh lalu
        use Carbon\Carbon;
    @endphp
    
    <div class="col-sm-12">
        {{-- cetak value detail_postingan, column judul_postingan --}}
        <h1>{{ $detail_postingan->judul_postingan }}</h1>
        {{-- cetak asset('') berarti memanggil folder public --}}
        <img src='{{ asset("storage/gambar_postingan/$detail_postingan->gambar_postingan") }}' width="100%" height="500px" alt="">

        {{-- cetak kapan postingan ini dibuat --}}
        {{-- untuk mencetak misalnya postingan ini ditulis 1 hari yang lalu --}}
        @php 
            // Untuk mencetak waktu sekarang
            $waktu_saat_ini = Carbon::now();
            // untuk mencetak kapan postingan ini dipublikasi_pada
            $waktu_postingan = Carbon::parse($detail_postingan->dipublikasi_pada);
        @endphp

        {{-- cetak value detail_postingan, column dipublikasi_pada --}}
        <p class="card-text float-right mt-1">{{ $waktu_postingan->diffForHumans($waktu_saat_ini) }}</p>

        {{-- cetak name penulis --}}
        {{-- cetak value detail_postingan yang berelasi dengan detail_user, column name --}}
        <p class="mt-1">Ditulis oleh: {{ $detail_postingan->user->name }}</p>

        {{-- cetak teks mentah dari detail_postingan, column konten_postingan --}}
        {!! $detail_postingan->konten_postingan !!}
    </div>
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
{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Artikel')


{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')

    <div class="container">
        <h1 class="mt-4 text-center">Artikel</h1>

        <div class="row">
            {{-- agar bisa menulis script php --}}
            @php
                // gunakan carbon untuk mengubah 2023-08-24 menjadi Kamis, 24 Agustus 2023
                use Carbon\Carbon;
                // aku butuh str agar misalnya ada 100 karakter akan menjadi 50 karakter dan diakhir ada ..., misalnya hahaha...
                use Illuminate\Support\Str;
            @endphp
        
            {{-- looping sebanyak jumlah semua_postingan --}}
            {{-- untukSetiap ($semua_postingan sebagai $postingan) --}}
            @foreach ($semua_postingan as $postingan)
                {{-- berarti di satu baris, ada 2 column --}}
                <div class="col-sm-4">
                    {{-- panggil route frontend.artikel.show, lalu kirimkan data berupa array, berisi value detail_ppstingan, column slug_postingan --}}
                    <a href="{{ route('frontend.artikel.show', [$postingan->slug_postingan]) }}">
                        <!-- Simple card -->
                        <div class="card">
                            {{-- cetak asset() berarti panggil folder public --}}
                            <img class="card-img-top img-fluid" src='{{ asset("storage/gambar_postingan/$postingan->gambar_postingan") }}' alt="{{ $postingan->judul_postingan }}">
                            <div class="card-body">
                                {{-- limit atau batas berarti karakter 65 dan seterusnya akan terpotong kemudian diganti ... --}}
                                {{-- cetak setiap detail_postingan, column judul_postingan --}}
                                <h5 class="card-title mb-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $postingan->judul_postingan }}">{{ Str::limit($postingan->judul_postingan, 65) }}</h5>
        
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
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection



{{-- @dorong value script ke @stack script --}}
@push('script')
    <script>

    </script>
@endpush
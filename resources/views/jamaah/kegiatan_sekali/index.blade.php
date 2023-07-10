{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Kegiatan Sekali')

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

    {{-- looping sebanyak jumlah semua_kegiatan_sekali --}}
    {{-- untukSetiap ($semua_kegiatan_sekali sebagai $kegiatan_sekali) --}}
    @foreach ($semua_kegiatan_sekali as $kegiatan_sekali)
        {{-- berarti di satu baris, ada 3 column --}}
        <div class="col-sm-4">
            <!-- Simple card -->
            <div class="card">
                {{-- cetak asset() berarti panggil folder public --}}
                <img class="card-img-top img-fluid" src='{{ asset("storage/gambar_kegiatan_sekali/$kegiatan_sekali->gambar_kegiatan") }}' alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title mb-3">Pengajian bersama ustadz abcdedg</h4>
                    {{-- cetak value detail kegiatan_sekali, column tanggal dan ubah format nya menjadi seperti Sunday, 26 June 2023 --}}
                    <p class="card-text mb-1 text-success">Tanggal: {{ Carbon::parse($kegiatan_sekali->tanggal)->format('l, d F Y') }}</p>
                    {{-- cetak value detail kegiatan_sekali, column jam_mulai dan jam_selesai, lalu hanya tampilkan jam dan menit --}}
                    <p class="card-text text-warning">Jam: {{ Carbon::createFromFormat('H:i:s', $kegiatan_sekali->jam_mulai)->format('H:i') }} - {{ Carbon::createFromFormat('H:i:s', $kegiatan_sekali->jam_selesai)->format('H:i') }}</p>
                </div>
            </div>
        </div>
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
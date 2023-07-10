{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Kegiatan Rutin')

@section('konten')
<div class="row">
    {{-- gunakan script php --}}
    @php
        // gunakan carbon untuk mengubah 2023-06-25 menjadi Sunday, 25 June 2023
        use Carbon\Carbon;
    @endphp

    {{-- looping sebanyak jumlah semua_kegiatan_rutin --}}
    {{-- untukSetiap ($semua_kegiatan_rutin sebagai $kegiatan_rutin) --}}
    @foreach ($semua_kegiatan_rutin as $kegiatan_rutin)
        {{-- berarti di satu baris, ada 3 column --}}
        <div class="col-sm-4">
            <!-- Simple card -->
            <div class="card">
                {{-- cetak asset() berarti panggil folder public --}}
                <img class="card-img-top img-fluid" src='{{ asset("storage/gambar_kegiatan_rutin/$kegiatan_rutin->gambar_kegiatan") }}' alt="Card image cap">
                <div class="card-body">
                    {{-- cetak value detail kegatan_sekali, column nama_kegiatan --}}
                    <h4 class="card-title mb-3">{{ $kegiatan_rutin->nama_kegiatan }}</h4>
                    {{-- cetak value detail kegiatan_rutin, column hari --}}
                    <p class="card-text mb-1 text-success">Setiap: {{ $kegiatan_rutin->hari }}</p>
                    {{-- cetak value detail kegiatan_rutin, column jam_mulai dan jam_selesai, lalu hanya tampilkan jam dan menit --}}
                    <p class="card-text text-warning">Jam: {{ Carbon::createFromFormat('H:i:s', $kegiatan_rutin->jam_mulai)->format('H:i') }} - {{ Carbon::createFromFormat('H:i:s', $kegiatan_rutin->jam_selesai)->format('H:i') }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('script')
<script>
    // tambahkan kedua baris kode berikut agar bilah samping nya runtuh atau sidebar collapse atau agar left menu nya menjadi kecil
    // panggil element body, lalu tambahkan class="enlarged"
    $("body").addClass("enlarged");
    // panggil element body, lalu tambah attribute data-tetap-membesar, value nya benar
    $("body").attr("data-keep-enlarged", "true");
</script>
@endpush
{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Detail Doa')

{{-- kirimkan value @bagian('konten') lalu nanti akan ditangkap oleh @yield('konten') --}}
@section('konten')
<div class="row">
    {{-- dalam satu baris hanya ada 1 column --}}
    <div class="col-sm-12">
        {{-- cetak value $detail_doa, column doa, pake ["doa"] karena dia adalah array numbering bukan array assosiatif --}}
        <h2>{{ $detail_doa["doa"] }}</h2>
        {{-- cetak value $detail_doa, column ayat --}}
        <h2 class="mb-3">{{ $detail_doa["ayat"] }}</h2>
        {{-- cetak value $detail_doa, column latin --}}
        <p>{{ $detail_doa["latin"]; }}</p>
        {{-- cetak value $detail_doa, column artinya --}}
        <p>Artinya: {{ $detail_doa["artinya"] }}</p>
    </div>
</div>
@endsection

    

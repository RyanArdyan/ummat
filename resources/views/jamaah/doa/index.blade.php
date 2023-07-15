{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Doa')

{{-- @push berfungsi mendorong value nya lalu nanti ditangap oleh @stack('css') --}}
@push('css')
{{-- cetak panggil, asset berarti panggil public --}}
<link rel="stylesheet" href="{{ asset('css_saya/style.css') }}">
@endpush

{{-- kirimkan value @bagian('konten') lalu nanti akan ditangkap oleh @yield('konten') --}}
@section('konten')
<div class="row">
    {{-- looping sebanyak jumlah semua_doa --}}
    {{-- untukSetiap ($semua_doa sebagai $doa) --}}
    @foreach ($semua_doa as $doa)
        {{-- berarti di satu baris, ada 3 column --}}
        <div class="col-sm-4">
            {{-- cetak panggil route doa.show, kirimkan value detail_doa, index id --}}
            <a href="{{ route('doa.show', [$doa["id"] ]) }}">
                <!-- cetak value detail_doa, column id -->
                <div class="card kartu_doa" data-doa-id="{{ $doa["id"] }}">
                    <div class="card-body jadikan_pointer">
                        {{-- cetak value detail doa, column doa --}}
                        <h4 class="card-title mb-3">{{ $doa["doa"] }}</h4>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection

@push('script')
<script>

</script>
@endpush
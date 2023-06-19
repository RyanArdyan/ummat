{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value section title ke parent nya yaitu layouts.app --}}
@section('title', 'Dashboard')

@section('konten')
<div class="row">
    <div class="col-sm-12">
        <h1>Grafik Pendapatan</h1> 
    </div>
</div>
@endsection

@push('script')
@endpush
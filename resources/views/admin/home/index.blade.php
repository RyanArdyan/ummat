    {{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value section title ke parenf nya yaitu layouts.app --}}
@section('title', 'Home')

@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- cetak value detail_user yang login, column name --}}
        <h3>Selamat datang {{ auth()->user()->name }}</h3>        
    </div>
</div>
@endsection

@push('script')
@endpush


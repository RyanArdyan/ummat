{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Formulir Donasi Manual')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="container d-flex flex-column min-vh-100">
        <h1 class="mt-4 text-center">Selesai</h1>

        {{-- cetak value variable $pesan_notifikasi yang dikirim dari controller  --}}
        <div class="alert alert-success mt-3">
            {{ $pesan_notifikasi }}
        </div>

        {{-- panggil route  --}}
        <a href="{{ route('donasi_manual.create') }}" class="btn btn-danger mb-2">Kembali</a>
        {{-- cetak, panggil route frontend.index --}}
        <a href="{{ route('frontend.index') }}" class="btn btn-danger">Lihat Daftar Pendonasi</a>
    </div>
@endsection

{{-- kasi margin  --}}

{{-- dorong value @dorong('script') ke @stack('script') milik parent nya --}}
@push('script')

@endpush


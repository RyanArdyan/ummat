{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Penceramah')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')

    <div class="container">
        <h1 class="mt-4 text-center">Penceramah</h1>

        {{-- Jika tidak ada baris data di table penceramah atau jika semua_penceramah, adalah kosong --}}
        @if($semua_penceramah->isEmpty())
            <p class="text-center">Belum ada penceramah.</p>
        {{-- Jika ada baris data di table penceramah atau jika semua_penceramah tidak kosong --}}
        @elseif (!$semua_penceramah->isEmpty())
            {{-- Table penceramah --}}
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Nama Penceramah</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Lakukan pengulangan terhadap $semua_penceramah sebagai $penceramah untuk mengambil setiap detail penceramah --}}
                    @foreach ($semua_penceramah as $penceramah)
                    <tr>
                        {{-- cetak $pengulangan->iterasi atau pengulangan nomor --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{-- cetak, panggil folder public/storage/foto_penceramah lalu panggil value detail penceramah, column foto_penceramah --}}
                            <img src='{{ asset("storage/foto_penceramah/$penceramah->foto_penceramah") }}' alt="Foto penceramah" width="50px" height="50px" class="img-thumbnail">
                        </td>
                        {{-- cetak setiap value $penceramah, column nama_penceramah --}}
                        <td>{{ $penceramah->nama_penceramah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endisset
    </div>
@endsection

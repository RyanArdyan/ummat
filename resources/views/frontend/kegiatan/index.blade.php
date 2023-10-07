{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Kegiatan')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')

    <div class="container">
        <h1 class="mt-4 mb-3 text-center">Kegiatan Rutin</h1>

        {{-- Jika tidak ada baris data di table kegiatan_rutin atau jika semua_kegiatan_rutin, adalah kosong --}}
        @if($semua_kegiatan_rutin->isEmpty())
            <p class="text-center">Belum ada kegiatan rutin.</p>
        {{-- Jika ada baris data di table kegiatan rutin atau jika semua_kegiatan_rutin tidak kosong --}}
        @elseif (!$semua_kegiatan_rutin->isEmpty())
            {{-- Table Kegiatan Rutin --}}
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Kegiatan</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Jam</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Lakukan pengulangan terhadap $semua_kegiatan_rutin sebagai $kegiatan_rutin untuk mengambil setiap detail kegiatan_rutin --}}
                    @foreach ($semua_kegiatan_rutin as $kegiatan_rutin)
                    <tr>
                        {{-- cetak $pengulangan->iterasi --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{-- cetak, panggil folder public/storage/gambar_kegiatan_rutin lalu panggil value detail kegiatan_rutin, column gambar_kegiatan --}}
                            <img src='{{ asset("storage/gambar_kegiatan_rutin/$kegiatan_rutin->gambar_kegiatan") }}' alt="Gambar Kegiatan Rutin" width="50px" height="50px" class="img-thumbnail">
                        </td>
                        {{-- cetak setiap value $kegiatan_rutin, column nama_kegiatan --}}
                        <td>{{ $kegiatan_rutin->nama_kegiatan }}</td>
                        {{-- cetak setiap value $kegiatan_rutin, column hari --}}
                        <td>{{ $kegiatan_rutin->hari }}</td>
                        {{-- cetak setiap value $kegiatan_rutin, column jam_mulai --}}
                        <td>{{ $kegiatan_rutin->jam_mulai }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endisset

        

        <h1 class="text-center mt-4 mb-3">Kegiatan Sekali</h1>
        {{-- Jika tidak ada baris data di table kegiatan_sekali atau jika semua_kegiatan_sekali, adalah kosong --}}
        @if($semua_kegiatan_sekali->isEmpty())
            <p class="text-center">Belum ada kegiatan sekali.</p>
        {{-- Jika ada baris data di table kegiatan sekali atau jika semua_kegiatan_sekali tidak kosong --}}
        @elseif (!$semua_kegiatan_sekali->isEmpty())
            {{-- Table Kegiatan sekali --}}
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Kegiatan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Jam</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Lakukan pengulangan terhadap $semua_kegiatan_sekali sebagai $kegiatan_sekali untuk mengambil setiap detail kegiatan_sekali --}}
                    @foreach ($semua_kegiatan_sekali as $kegiatan_sekali)
                    <tr>
                        {{-- cetak $pengulangan->iterasi --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{-- cetak, panggil folder public/storage/gambar_kegiatan_sekali lalu panggil value detail kegiatan_sekali, column gambar_kegiatan --}}
                            <img src='{{ asset("storage/gambar_kegiatan_sekali/$kegiatan_sekali->gambar_kegiatan") }}' alt="Gambar Kegiatan sekali" width="50px" height="50px" class="img-thumbnail">
                        </td>
                        {{-- cetak setiap value $kegiatan_sekali, column nama_kegiatan --}}
                        <td>{{ $kegiatan_sekali->nama_kegiatan }}</td>
                        {{-- cetak setiap value $kegiatan_sekali, column tanggal --}}
                        <td>{{ $kegiatan_sekali->tanggal }}</td>
                        {{-- cetak setiap value $kegiatan_sekali, column jam_mulai --}}
                        <td>{{ $kegiatan_sekali->jam_mulai }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

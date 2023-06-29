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
    {{-- termasuk jika doa.modal_detail dipanggil --}}
    @includeIf('jamaah.doa.modal_detail')

    {{-- looping sebanyak jumlah semua_doa --}}
    {{-- untukSetiap ($semua_doa sebagai $doa) --}}
    @foreach ($semua_doa as $doa)
        {{-- berarti di satu baris, ada 3 column --}}
        <div class="col-sm-4">
            <!-- cetak value detail_doa, column doa_id -->
            <div class="card kartu_doa" data-doa-id="{{ $doa->doa_id }}" data-toggle="modal" data-target=".modal_detail_doa">>
                <div class="card-body jadikan_pointer">
                    {{-- cetak value detail kegatan_sekali, column nama_doa --}}
                    <h4 class="card-title mb-3">{{ $doa->nama_doa }}</h4>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('script')
<script>
    // jika .kartu_doa di click maka jalankan fungsi
    $(".kartu_doa",).on("click",  function() {
        // ambil value attribute data-doa-id
        // berisi panggil .kartu_doa, lalu cetak value attribute data-doa-id
        let doa_id = $(this).data("doa-id");

        // jquery lakukan ajax untuk mengambil detail_doa
        $.ajax({
            // url panggil url /doa lalu kirimkan value variable doa_id
            // tanda backtiq (``) bisa mencetak value variable di dalam string menggunakan ${}
            url: `/doa/${doa_id}`,
            type: 'GET',
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
        .done(function(resp) {
            // panggil #nama_doa lalu text nya diisi tanggapan.detaiL_doa.nama_doa
            $("#nama_doa").text(resp.detail_doa.nama_doa);
            // panggil #bacaan_arab lalu text nya diisi tanggapan.detaiL_doa.bacaan_arab
            $("#bacaan_arab").text(resp.detail_doa.bacaan_arab);
            // panggil #bacaan_latin lalu text nya diisi tanggapan.detaiL_doa.bacaan_latin
            $("#bacaan_latin").text(resp.detail_doa.bacaan_latin);
            // panggil #arti_doanya lalu text nya diisi tanggapan.detaiL_doa.arti_doanya
            $("#arti_doanya").text(`Artinya: ${resp.detail_doa.arti_doanya}`);
        });
    });
</script>
@endpush
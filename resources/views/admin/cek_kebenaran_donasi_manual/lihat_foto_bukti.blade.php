{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Lihat Foto Bukti')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
<style>
</style>
@endpush

{{-- kirimkan value @bagian('konten') ke dalam @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            <div class="mb-3 mt-2">
                {{-- jika di click maka panggil route admin.cek_kebenaran_donasi.manual.index --}}
                <a href="{{ route('admin.cek_kebenaran_donasi_manual.index') }}" class="btn btn-sm btn-warning">Kembali</a>
                <button id="tombol_benar" class="btn btn-sm btn-success" type="button">Benar</button>
                <button id="tombol_palsu" class="btn btn-sm btn-danger" type="button">Palsu</button>
            </div>
            {{-- cetak, asset berarti memanggil folder public --}}
            <img src='{{ asset("storage/donasi_manual/$foto_bukti") }}' width="100%" alt="">
        </div>
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


        // fungsi proses_pengecekan_status, jadi jika user click tombol "Benar" maka ubah value detail_donasi_manual, column status menjadi "Benar", kalau user click tombol "Palsu" maka hapus value detail_donasi
        // ada 1 parameter, isinya "Benar" atau "Palsu"
        function proses_pengecekan_status(status) {
            // jquery, lakukan ajax
            $.ajax({
                // panggil url berikut
                url: "/cek-kebenaran-donasi-manual/proses-pengecekan-status",
                // panggil route kirim
                type: "POST",
                // kirimkan data berupa object, karena aku mengirimkan object secara manual maka tidak butuh processData dan kawan2x
                data: {
                    // laravel mewajibkan kemaanan dari serangan csrf
                    // berisi cetak csrf_token()
                    "_token": "{{ csrf_token() }}",
                    // berisi value variable status, anggaplah berisi "Donasi Benar"
                    "status": status,
                    // berisi cetak value variable donasi_manual_id
                    "donasi_manual_id": "{{ $donasi_manual_id }}"
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // jika berhasil menyimpan penceramah
                // jika resp.status sama dengan 200
                if (resp.status === 200) {
                    // lakukan pengecekan jika status nya benar maka 
                    // jika value tanggapa.pesan sama dengan string berikut
                    if (resp.pesan === "Donasi yang dilakukan adalah benar") {
                        // pindah ke route berikut lalu kirimkan argument berikut
                        window.location.href = "{{ route('admin.cek_kebenaran_donasi_manual.tampilkan_notifikasi', ['Donasi nya benar']) }}";
                    }
                    // jika value tanggapa.pesan sama dengan string berikut
                    else if(resp.pesan === 'Donasi yang dilakukan adalah palsu') {
                        // pindah ke route berikut lalu kirimkan argument berikut
                        window.location.href = "{{ route('admin.cek_kebenaran_donasi_manual.tampilkan_notifikasi', ['Donasi nya palsu']) }}";
                    };
                };
            });
        };

        // jika id tombol_benar di click maka jalankan fungsi berikut
        $("#tombol_benar").on("click", function() {
            // panggil fungsi proses_pengecekan_status lalu kirimkan "Benar"
            proses_pengecekan_status("Benar");
        });

        // jika id tombol_palsu di click maka jalankan fungsi berikut
        $("#tombol_palsu").on("click", function() {
            // panggil fungsi proses_pengecekan_status lalu kirimkan "Palsu"
            proses_pengecekan_status("Palsu");
        });
    </script>
@endpush
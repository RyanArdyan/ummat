{{-- @memperluas('frontend/layouts/app') yaitu parent nya --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Menunggu Donasi')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="container d-flex flex-column min-vh-100">
        <h1 class="my-4 text-center">Menunggu Donasi</h1>

        <div class="row">
                {{-- jika value variable $message sama dengan string berikut --}}
                @if ($message === 'Menunggu pembayaran akan muncul jika anda melakukan donasi tapi belum melakukan pembayaran donasi') 
                    {{-- cetak value variable $message --}}
                    <p>{{ $message }}</p>
                {{-- lain jika value variable pesan sama dengan string berikut --}}
                @elseif ($message === 'Ada donasi yang harus dibayar')
                    {{-- looping value variable beberapa_menunggu_pembayaran --}}
                    {{-- @untukSetiap, $variable sebagai $variable --}}
                    @foreach ($beberapa_menunggu_pembayaran as $menunggu_pembayaran)
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Detail Donasi</h5>
                                    {{-- cetak value variable $nama_user_yg_login atau cetak value detail_donasi, yg berelasi dengan table users, column name --}}
                                    <p><b>Nama: </b>{{ $menunggu_pembayaran->user->name }}</p>
                                    {{-- cetak value detail_menunggu_pembayaran, column jumlah_donasi --}}
                                    <p><b>Jumlah Donasi:</b> {{ $menunggu_pembayaran->jumlah_donasi }}</p>
                                    <p><b>Pesan Donasi: </b>{{ $menunggu_pembayaran->pesan_donasi }}</p>
                                    <button data-snap-token="{{ $menunggu_pembayaran->snap_token }}" class="pay-button btn btn-success mt-2">Donasi Sekarang</button>
                                    {{-- cetak, panggil route donasi.buat --}}
                                    <a href="{{ route('donasi.create') }}" class="btn btn-danger mt-2">Kembali</a>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach
                @endif
                
        </div>
    </div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') milik parent nya --}}
@push('script')
    {{-- payment gateway menggunakan midtrans --}}
    <!-- ganti value attribute data-client-key dengan kunci klien Anda -->
    {{-- cetak value constanta client_key milik file config/midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    {{-- package sweetalert2 untuk menampilkan notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





    <script>
        // ambil semua class pay-button
        // berisi dokumen.querySeleksiSemua class pay-button
        var payButton = document.querySelectorAll('.pay-button');
        // looping semua tomholBayar, parameter button berarti setiap class pay-button
        payButton.forEach(function(button) {
            // setiap payButton yg di click
            // tombol.tambahAcaraPendengar("click", fungsi())
            button.addEventListener('click', function() {
                // panggil tombol #pay-button lalu ambil value dari attribute data-snap-token
                var snapToken = button.getAttribute('data-snap-token');

        
                // Memicu popup sekejap. @TODO: Ganti TRANSACTION_TOKEN_HERE dengan token transaksi Anda
                // jendela.jepret.bayar, cetak value variable snapToken milik PHP
                window.snap.pay(snapToken, {
                    // ketika user berhasil membayar
                    // padaSukses maka jalankan fungsi dan ambil hasil nya
                    onSuccess: function(result) {
                        /* Anda dapat menambahkan implementasi Anda sendiri disini */
                        console.log(result);
        
                        // tampilkan notifikasi menggunakan package sweetalert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Donasi',
                            text: 'Terima kasih atas kebaikan anda.',
                        })
                        // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                        .then((result) => {
                            // jika aku click oke pada pop up sweetalert maka
                            // jika hasilnya dikonfirmasi maka
                            if (result.isConfirmed) {
                                // pindahkan ke route frontend.index
                                // jendela.lokasi.href
                                location.href = `{{ route('frontend.index') }}`;
                            };
                        });
                    },
                    // pending berarti user sudah click tombol donasi di url /frontend/donasi/create tapi belum melakukan pembayaran di url /donasi/faktur/{kode_acak_midtrans}
                    // padaMenungguKeputusan, jalankan fungsi, ambil hasil nya lewat parameter hasil.
                    onPending: function(result) {
                        // tampilkan notifikasi menggunakan package sweetalert
                        Swal.fire({
                            icon: 'info',
                            title: 'Hmm',
                            text: 'Menunggu pembayaran anda.',
                        });
        
                        // cetak value parameter hasil
                        console.log(result);
                    },
                    // ini berarti ada yg salah dengan kode ku, sehingga payment midtrans nya tidak berjalan
                    // padaKesalahan, jalankan fungsi, dan ambil hasil nya lewat parameter hasil
                    onError: function(result) {
                        // tampilkan notifikasi menggunakan package sweetalert
                        Swal.fire({
                            icon: 'error',
                            title: 'Kode Error',
                            text: 'Pembayaran gagal!',
                        });
                        console.log(result);
                    },
                    // ini berarti aku click "Donasi Sekarang" lalu modal midtrans yang berisi banyak metode terbuka, lalu aku menutup modal nya lagi
                    // padaPenutupan, jalankan fungsi berikut
                    onClose: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kisama!',
                            text: 'Segera lakukan pembayaran dalam 24 jam.',
                        });
                    }
                });
            });
        });
    </script>
@endpush





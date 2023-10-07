{{-- @memperluas('frontend/layouts/app') yaitu parent nya --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Detail Donasi')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="container d-flex flex-column min-vh-100">
        <h1 class="mt-4 text-center">Detail Donasi</h1>

        <div class="row">
            <div class="col-sm-12">
                {{-- fitur notifikasi, jadi setelah user click tombol donasi pada url /frontend/donasi/create maka tampilkan notifikasi "Silahkan lakukan pembayaran donasi." --}}
                {{-- jika ada sesi status yang dikirim dari DonasiIController, method store maka --}}
                @if (session('status'))
                    {{-- cetak value variable sesi status --}}
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif
    
                <div class="card" style="width: 500px">
                    <div class="card-body">
                        <h5 class="card-title">Detail Donasi</h5>
                        {{-- cetak value variable $nama_user_yg_login --}}
                        <p><b>Nama: </b>{{ $nama_user_yg_login }}</p>
                        <p><b>Jumlah Donasi:</b> {{ $jumlah_donasi }}</p>
                        <p><b>Pesan Donasi: </b>{{ $pesan_donasi }}</p>
                        <button id="pay-button" class="btn btn-success mt-2">Donasi Sekarang</button>
                        {{-- cetak, panggil route donasi.buat --}}
                        <a href="{{ route('donasi.create') }}" class="btn btn-danger mt-2">Kembali</a>
                    </div>
                </div>
            </div>
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

    {{-- script ku --}}
    <script>
        // Misalnya pemicu pada tombol yang diklik, atau kapan saja Anda butuhkan.
        // berisi dokument.dapatkanElementDenganId #pay-button
        var payButton = document.getElementById('pay-button');
        // #pay-button ketika di click maka jalankan fungsi berikut
        // payButton.tambahAcaraPendengar("click", fungsi())
        payButton.addEventListener('click', function() {
            // Memicu popup sekejap. @TODO: Ganti TRANSACTION_TOKEN_HERE dengan token transaksi Anda
            // jendela.jepret.bayar, cetak value variable snapToken milik PHP
            window.snap.pay("{{ $snapToken }}", {
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
            })
        });
    </script>
@endpush





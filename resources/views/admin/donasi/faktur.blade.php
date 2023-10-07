{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app --}}
@section('title', 'Faktur Donasi')

{{-- @dorong('css') berfungsi mendorong value nya ke @stack('css') --}}
@push('css')
    <!-- ganti value attribute data-client-key dengan kunci klien Anda -->
    {{-- cetak value constanta client_key milik file config/midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@endpush

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
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
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <script type="text/javascript">
        // Misalnya pemicu pada tombol yang diklik, atau kapan saja Anda butuhkan.
        // berisi dokument.dapatkanElementDenganId #pay-button
        var payButton = document.getElementById('pay-button');
        // #pay-button ketika di click maka jalankan fungsi berikut
        // payButton.tambahAcaraPendengar("click", fungsi())
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            // jendela.jepret.bayar, cetak value variable snapToken milik PHP
            window.snap.pay("{{ $snapToken }}", {
                // ketika user berhasil membayar
                // ketikaSukses maka jalankan fungsi dan ambil hasil nya
                onSuccess: function(result) {
                    /* Anda dapat menambahkan implementasi Anda sendiri disini */
                    console.log(result);
                    // notifikasi menggunakan package web sweetalert2
                    Swal.fire(
                        'Berhasil Donasi',
                        'Terima kasih atas kebaikan anda.',
                        'success'
                    );
                    
                    // setelah 2 detik, 500 mildetik maka jalankan fungsi berikut
                    setTimeout(() => {
                        // arahkan ke lokasi donasi.index
                        // window.lokasi.href = ke route donasi.index
                        window.location.href = "{{ route('frontend.index') }}";
                    }, 2500);
                },
                // pending berarti user sudah click tombol donasi di url /frontend/donasi/create tapi belum melakukan pembayaran di url /donasi/faktur/{kode_acak_midtrans}
                // padaMenungguKeputusan, jalankan fungsi, ambil hasil nya lewat parameter hasil.
                onPending: function(result) {
                    /* You may add your own implementation here, anda mungkin menambahkan kode impelementasi anda sendiri disini */
                    alert("Menunggu pembayaran anda.");
                    // cetak value parameter hasil
                    console.log(result);
                },
                // ini berarti ada yg salah dengan kode ku, sehingga payment midtrans nya tidak berjalan
                // padaKesalahan, jalankan fungsi, dan ambil hasil nya lewat parameter hasil
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                    console.log(result);
                },
                // ini berarti aku click "Donasi Sekarang" lalu modal midtrans yang berisi banyak metode terbuka, lalu aku menutup modal nya lagi
                // padaPenutupan, jalankan fungsi berikut
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            })
        });
    </script>
@endpush

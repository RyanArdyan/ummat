{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')



@push('css')
    {{-- toastr css --}}
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
@endpush


@php
    
@endphp

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', $detail_postingan->judul_postingan  )

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <div class="container">
        <div class="row">
            {{-- gunakan script php agar bisa menulis kode php di .blade atau html --}}
            @php
                // gunakan carbon agar bisa menampilkan postingan ini dibuat 1 hari yang lalu
                use Carbon\Carbon;

                // cetak kapan postingan ini dibuat
                // untuk mencetak misalnya postingan ini ditulis 1 hari yang lalu
                // Untuk mencetak waktu sekarang
                $waktu_saat_ini = Carbon::now();
                // untuk mencetak kapan postingan ini dipublikasi_pada
                $waktu_postingan = Carbon::parse($detail_postingan->dipublikasi_pada);
            @endphp
            
            <div class="col-sm-12">
                {{-- cetak value detail_postingan, column judul_postingan --}}
                <h1 class="my-4">{{ $detail_postingan->judul_postingan }}</h1>
                {{-- cetak asset('') berarti memanggil folder public --}}
                <img src='{{ asset("storage/gambar_postingan/$detail_postingan->gambar_postingan") }}' width="100%" height="500px" alt="">
        
                
                @php 
                    
                @endphp
        
                {{-- cetak value detail_postingan, column dipublikasi_pada --}}
                <p class="card-text mt-1">Ditulis oleh {{ $detail_postingan->user->name }} pada {{ $waktu_postingan->diffForHumans($waktu_saat_ini) }}</p>
        
                {{-- cetak name penulis --}}
                {{-- cetak value detail_postingan yang berelasi dengan detail_user, column name --}}
                <p class="mt-1"></p>
        
                {{-- cetak teks mentah dari detail_postingan, column konten_postingan --}}
                {!! $detail_postingan->konten_postingan !!}
                
                <hr>
                {{-- Fitur Komentar --}}
                <h3>Tampilan Komentar</h3>
                {{-- penting #komentar_terbaru aku gunakan untuk menampilkan komentar terbaru menggunakan javascript --}}
                <div id="komentar_terbaru" class="mb-3">
                    
                </div>
                {{-- panggil url berikut lalu kirimkan value detail_postingan, column postingan_id --}}
                <a href="/frontend/artikel/halaman-semua-komentar/{{ $detail_postingan->postingan_id }}">Lihat Semua Komentar</a>

                <hr>
                <h3>Tambahkan Komentar</h3>
                <form id="form_komentar">
                    {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                    @csrf
                    <div class="form-group mb-2">
                        <textarea class="komentarnya_input input form-control" name="komentarnya"></textarea>
                        {{-- cetak value variable detail_postingan, column postingan_id --}}
                        <input type="hidden" name="postingan_id" value="{{ $detail_postingan->postingan_id }}" />
                        <span class="pesan_error text-danger komentarnya_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" value="Selesai">Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- @dorong value script ke @stack script --}}
@push('script')
    {{-- sweetalert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- toastr js --}}
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        // Fungsi untuk mengonversi format tanggal jadi dari 2023-07-22T07:52:55.000000 akan menjadi Sabtu, 22 Juli 2023 07:52
        function formatDateTime(inputDateTime) {
            // Ubah string tanggal dan waktu menjadi objek Date
            var dateTime = new Date(inputDateTime);

            // Daftar nama hari
            var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

            // Daftar nama bulan
            var months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            // Ambil informasi dari objek Date
            var dayOfWeek = days[dateTime.getDay()];
            var day = dateTime.getDate();
            var month = months[dateTime.getMonth()];
            var year = dateTime.getFullYear();
            var hours = addLeadingZero(dateTime.getHours());
            var minutes = addLeadingZero(dateTime.getMinutes());

            // Format tanggal dan waktu yang diinginkan
            var formattedDateTime = dayOfWeek + ", " + day + " " + month + " " + year + " " + hours + ":" + minutes;
            return formattedDateTime;
        };

        // Fungsi untuk menambahkan angka nol di depan angka satu digit (0-9)
        function addLeadingZero(number) {
            return number < 10 ? "0" + number : number;
        };


        // untuk mengambil value detail_komentar terbaru
        function read_komentar_terbaru() {
            // berisi cetak value detail_postingan, column postingan_id angaplah 1
            let postingan_id = "{{ $detail_postingan->postingan_id }}";

            // jquery lakukan ajax
            $.ajax({
                // berisi panggil url berikut lalu kirimkan value variable postingan_id untuk memgambil detail_komentar terbaru yg terkait
                url: `/frontend/artikel/detail-komentar-terbaru/${postingan_id}`,
                // panggil route type get
                type: "GET",
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // jika value tanggapan.pesan sama dengan "Belum ada komentar" maka
                if (resp.message === "Belum ada komentar") {
                    // buat element p
                    let paragraph = "<p>Belum ada komentar.</p>"
                    // panggil #komentar_terbaru lalu tambahkan value variable html ke dalam nya sebagai anak pertama
                    $("#komentar_terbaru").prepend(paragraph);
                }
                // lain jika ada komentar
                else {
                    // berisi value resp.detail_komentar_terbaru
                    let detail_komentar_terbaru = resp.detail_komentar_terbaru;


                    // berisi tampilan komentar seperti nama penulis komentar, waktu dia membuat komentar dan komentar nya
                    let tampilan_komentar_terbaru = 
                        `
                            <div class="display-comment" style="width: 400px;">
                                <div class="d-flex align-items-center mb-1">
                                    <p class="fw-bold mb-0 me-2">${detail_komentar_terbaru.user.name}</p>
                                    <p class="fw-light m-0">${formatDateTime(detail_komentar_terbaru.created_at)}</p>
                                </div>

                                <p class="my-0">${detail_komentar_terbaru.komentarnya}</p>
                            </div>
                        `;


                    // panggil #komentar_terbaru lalu tambahkan value variable tampilan_komentar_terbaru sebagai anak pertama
                    $("#komentar_terbaru").prepend(tampilan_komentar_terbaru);
                };
            });
        };
        // panggil fungsi reload_komentar
        read_komentar_terbaru();


        // jika formulir komentar dikirim
        // jika #form_komentar dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_komentar").on("submit", function(e) {
            // acara cegah bawaannya yaitu reload
            e.preventDefault();
            // jquery, lakukan ajax
            $.ajax({
                // url panggil route frontend.artikel.simpan_komentar
                url: "{{ route('frontend.artikel.simpan_komentar') }}",
                // panggil route kirim
                type: "POST",
                // kirimkan data dari #form_komentar, otomatis membuat objek atau {}
                data: new FormData(this),
                // aku butuh 3 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
                // prosesData: salah,
                processData: false,
                contentType: false,
                cache: false
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapam nya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika resp.status sama dengan 0
                if (resp.status === 0) {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire("Anda belum menulis komentar.");
                }
                // jika berhasil menyimpan kategori sekali
                // lain jika resp.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_komentar index ke 0 lalu atur ulang semua input
                    $("#form_komentar")[0].reset();
                    // notifikasi
                    // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                    toastr.success(`${resp.pesan}.`);
                    // hapus semua anak dari #komentar_terbaru
                    $("#komentar_terbaru").empty();
                    // panggil fungsi read_komentar_terbaru agar menampilkan komentar terbaru
                    read_komentar_terbaru();
                };
            });
        });
    </script>
@endpush

{{-- aku tidak perlu carrousel tapi aku butuh copas /views/frontend/artikel/detail_artikel, copas tipis-tipis --}}
{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Detail Postingan')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- @bagian('konten') berfungsi mengirimkan value nya ke dalam @yield('konten') --}}
@section('konten')
<div class="row">
    {{-- gunakan script php agar bisa menulis kode php di .blade atau html --}}
    @php
        // gunakan carbon agar bisa menampilkan postingan ini dibuat 1 hari yang lalu
        use Carbon\Carbon;
    @endphp
    
    <div class="col-sm-12">
        {{-- cetak value detail_postingan, column judul_postingan --}}
        <h1>{{ $detail_postingan->judul_postingan }}</h1>
        {{-- cetak asset('') berarti memanggil folder public --}}
        <img src='{{ asset("storage/gambar_postingan/$detail_postingan->gambar_postingan") }}' width="100%" height="500px" alt="">

        {{-- cetak kapan postingan ini dibuat --}}
        {{-- untuk mencetak misalnya postingan ini ditulis 1 hari yang lalu --}}
        @php 
            // Untuk mencetak waktu sekarang
            $waktu_saat_ini = Carbon::now();
            // untuk mencetak kapan postingan ini dipublikasi_pada
            $waktu_postingan = Carbon::parse($detail_postingan->dipublikasi_pada);
        @endphp

        {{-- cetak value detail_postingan, column dipublikasi_pada --}}
        <p class="card-text float-right mt-1">{{ $waktu_postingan->diffForHumans($waktu_saat_ini) }}</p>

        {{-- cetak name penulis --}}
        {{-- cetak value detail_postingan yang berelasi dengan detail_user, column name --}}
        <p class="mt-1">Ditulis oleh: {{ $detail_postingan->user->name }}</p>

        {{-- cetak teks mentah dari detail_postingan, column konten_postingan --}}
        {!! $detail_postingan->konten_postingan !!}

        {{-- Fitur Komentar --}}
        <h3>Tampilan Komentar</h3>
        {{-- termasuk tampilan jamaah.postingan.komentar, kirimkan data berupa array --}}
        {{-- @include('jamaah.postingan.komentar', [
            // ambil semua komentar yang ditulis di suatu postingan
            // key semua_komentar berisi value $detail_postingan yang berelasi dengan komentar lewat models/postingan, method komentar
            'semua_komentar' => $detail_postingan->komentar,
            // key postingan_id berisi value variable $detail_postingan, column postingan_id
            'postingan_id' => $detail_postingan->postingan_id
        ]) --}}
        {{-- penting #komentar_terbaru aku gunakan utnuk menampilkan komentar terbaru menggunakan javascript --}}
        <div id="komentar_terbaru">
            
        </div>
        <hr>
        <h3>Tambahkan Komentar</h3>
        {{-- cetak panggil route postingan.simpan_komentar --}}
        <form id="form_komentar">
            {{-- laravel mewajibkan keamanan dari serangan csrf --}}
            @csrf
            <div class="form-group">
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
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
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



    // tambahkan kedua baris kode berikut agar bilah samping nya runtuh atau sidebar collapse atau agar left menu nya menjadi kecil
    // panggil element body, lalu tambahkan class="enlarged"
    $("body").addClass("enlarged");
    // panggil element body, lalu tambah attribute data-tetap-membesar, value nya benar
    $("body").attr("data-keep-enlarged", "true");

    

    // untuk mengambil value detail_komentar terbaru
    function read_komentar_terbaru() {
        // berisi cetak value detail_postingan, column postingan_id angaplah 1
        let postingan_id = "{{ $detail_postingan->postingan_id }}";

        // jquery lakukan ajax
        $.ajax({
            // berisi panggil url /postingan/detail-komentar-terbaru lalu kirimkan value variable postingan_id untuk memgambil detail_komentar terbaru yg terkait
            url: `/postingan/detail-komentar-terbaru/${postingan_id}`,
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
                            <p class="float-right">${formatDateTime(detail_komentar_terbaru.created_at)}</p>
                            <p>${detail_komentar_terbaru.user.name}</p>
                            <strong class="mt-2">${detail_komentar_terbaru.komentarnya}</strong>
                            <a href="/postingan/halaman-semua-komentar/${postingan_id}" class="btn btn-warning" style="width: 400px;">Lihat Semua Komentar</a>
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
            // url panggil route postingan.simpan_komentar
            url: "{{ route('postingan.simpan_komentar') }}",
            // panggil route kirim
            type: "POST",
            // kirimkan data dari #form_komentar, otomatis membuat objek atau {}
            data: new FormData(this),
            // aku butuh 3 baris kode berikut, kalau membuat objek secara manual maka tidak butuh 3 baris kode berikut
            // prosesData: salah,
            processData: false,
            contentType: false,
            cache: false,
            // sebelum kirim, hapus validasi error dulu
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .input lalu hapus .is-invalid
                $(".input").removeClass("is-invalid");
                // panggil .pesan_error lalu kosongkan textnya
                $(".pesan_error").text("");
            }
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
                // panggil fungsi read_komentar_terbaru
                read_komentar_terbaru();
            };
        });
    });
</script>
@endpush
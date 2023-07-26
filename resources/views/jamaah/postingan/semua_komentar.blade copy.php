{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Semua Komentar')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- @bagian('konten') berfungsi mengirimkan value nya ke dalam @yield('konten') --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        <h3>Tambahkan Komentar</h3>
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

        <hr>

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
        {{-- penting #semua_komentar aku gunakan utnuk menampilkan komentar terbaru menggunakan javascript --}}
        <div id="semua_komentar"></div>
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

    // untuk mengambil semua data komentar
    function reload_komentar() {
        // brisi cetak value detail_postingan, column postingan_id
        let postingan_id = "{{ $detail_postingan->postingan_id }}";

        // lakukan ajax
        $.ajax({
            // berisi panggil url /postingan/semua-komentar lalu kirimkan value detail_postingan, column postingan_id untuk memgambil semua komentar yg terkait
            url: `/postingan/read-semua-komentar/${postingan_id}`,
            // panggil route type get
            type: "GET",
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
        .done(function(resp) {
            // cetak tanggapan.semua_komentar
            console.log(resp.semua_komentar);

            // berisi string agar aku bisa jadikan dia seebagai wadah
            let wadah = "";

            // berisi value resp.semua_komentar
            let semua_komentar = resp.semua_komentar;
            // lakukan looping terhadap value variable semua_komentar, parameter item berisi setiap value detail_komentar, parameter index berisi angka indexnya dimulai dari 0
            // semua_komentar.untukSetiap, fungsi, barang, index
            semua_komentar.forEach(function(item, index) { 
                wadah += 
                `
                    <div class="display-comment mb-5" style="width: 400px;">
                        <strong class="float-right">${formatDateTime(item.created_at)}</strong>
                        <strong>${item.user.name}</strong>
                        <p>${item.komentarnya}</p>
                        <form class="form_balas">
                            <div class="form-group">
                                <input style="width: 400px" type="text" name="komentarnya" class="form-control" autocomplete="off" />
                                <input type="hidden" name="postingan_id" value="${item.postingan_id}" />
                                <input type="hidden" name="parent_id" value="${item.komentar_id}" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning">Balas</button>
                            </div>
                        </form>
                        <h4 style="margin-left: 40px">Tampilan Balasan</h4>`;
                        // lakukan pengulangan kepada semua balasan di suatu komentar
                        // detail_komentar yang berelasi dengan semua_balasan lewat models/komentar, method balasan, untukSetiap, fungsi, item2 berisi setiap detail_komentar_balasan, parameter index2 berisi index dimulai dari 0
                        item.balasan.forEach(function(item2, index2) {
                            // panggil variable wadah lalu value nya ditambah string yang berisi html berikut
                            wadah += `
                                <div style="margin-left: 40px">
                                    <strong>${item2.user.name}</strong>
                                    <p>${item2.komentarnya}</p>
                                </div>
                            `;
                        });
                        // panggil variable wadah lalu value nya ditambah string yang berisi html berikut
                        wadah += `<hr>
                    </div>
                `;
            });
            // panggil #semua_komentar lalu tambahkan value variable wadah sebagai anak pertama
            $("#semua_komentar").prepend(wadah);
        });
    // });
    };
    // panggil fungsi reload_komentar
    reload_komentar();

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
            // jika berhasil menyimpan komentar
            // lain jika resp.status sama dengan 200
            else if (resp.status === 200) {
                // reset formulir
                // panggil #form_komentar index ke 0 lalu atur ulang semua input
                $("#form_komentar")[0].reset();
                // notifikasi
                // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                toastr.success(`${resp.pesan}.`);
                // hapus semua anak dari #semua_komentar
                // panggil #semua_komentar lalu kosongkan atau hapus semua anak nya
                $("#semua_komentar").empty();
                // panggil fungsi reload_komentar
                reload_komentar();
            };
        });
    });

    // fitur balas komentar
    // jika document di kirim yang class nya adalah .form_balas maka jalankan fungsi berikut dan ambil event nya
    $(document).on("submit", ".form_balas", function(e) {
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
            // laravel mewajibkan keamanan dari serangan csrf jadi aku mengirim csrf di script
            // kepala-kepala berisi object
            headers: {
                // key X-CSRF-TOKEN berisi panggil meta attribute name berisi csrf-token lalu ambll value attribute content
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapam nya
        .done(function(resp) {
            // jika validasi menemukan error
            // jika resp.status sama dengan 0
            if (resp.status === 0) {
                // notifikasi menggunakan sweetalert
                Swal.fire("Anda belum menulis komentar.");
            }
            // jika berhasil menyimpan kategori sekali
            // lain jika resp.status sama dengan 200
            else if (resp.status === 200) {
                // reset formulir
                // panggil #form_tambah index ke 0 lalu atur ulang semua input
                $(".form_balas")[0].reset();
                // notifikasi
                // panggil toastr tipe sukses dan tampilkan pesannya menggunakan value dari tanggapan.pesan
                toastr.success(`${resp.pesan}.`);
                // hapus semua anak dari #semua_komentar
                $("#semua_komentar").empty();
                // panggil fungsi reload_komentar
                reload_komentar();
            };
        });
    });
</script>
@endpush

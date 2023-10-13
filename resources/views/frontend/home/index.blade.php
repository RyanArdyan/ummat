{{-- @memperluas('frontend/layouts/index') --}}
@extends('frontend.layouts.app')

{{-- kirimkan value @bagian konten ke @yield konten --}}
@section('konten')
<!-- carousel -->
{{-- @termasuk view berikut --}}
@include('frontend.layouts.carousel')

{{-- Donasi --}}
<div class="container">
    <section id="donasi" class="mb-5">
        {{-- Jadwal Adzan --}}
        <h5 class="text-center mt-5 mb-4 text-normal"><u>Ayo Berdonasi Hari Ini</u></h5>

        {{-- arahkan ke route donasi-manual/create  --}}
        <a href="/donasi-manual/create" class="btn btn-primary btn-sm mb-3">Saya Akan Berdonasi</a>

        {{-- @termasukJika dipanggil frontend.home.ubah_periode_donasi  --}}
        @includeIf('frontend.home.ubah_periode_donasi')

        {{-- Jika tombol Ubah Periode di click maka panggil modal ubah periode --}}
        <button id="tombol_ubah_periode" type="button" class="btn btn-primary btn-sm mb-3">
           Ubah Periode
        </button>

        <p>Daftar nama pendonasi pada bulan ini.</p>

        <div id="div_ubah_periode">
            <div class="table-responsive">
                <table id="table_donasi" class="table table-sm table-bordered table-hover">
                    <thead class="bg-primary">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama Pendonasi</th>
                            <th scope="col">Jumlah Donasi</th>
                            <th scope="col">Pesan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    {{-- Jadwal Adzan Di Kota Pontianak --}}
    <div class="row">
        {{-- Jadwal Adzan --}}
        <h5 class="text-center mt-5 mb-4 text-normal"><u>Jadwal Adzan di Kota Pontianak Hari Ini</u></h5>

        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Shubuh</th>
                    <th scope="col">Dzuhur</th>
                    <th scope="col">Ashar</th>
                    <th scope="col">Maghrib</th>
                    <th scope="col">Isya</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    {{-- cetak array jadwal_adzan, index ke 0 yang didalamnya ada index subuh --}}
                    <td>{{ $jadwal_adzan[0]['subuh'] }}</td>
                    <td>{{ $jadwal_adzan[0]['dzuhur'] }}</td>
                    <td>{{ $jadwal_adzan[0]['ashar'] }}</td>
                    <td>{{ $jadwal_adzan[0]['maghrib'] }}</td>
                    <td>{{ $jadwal_adzan[0]['isya'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    
</div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
<script>
    // read daftar donasi_manual
    // berisi panggil table donasi, gunakan datatable
    let table = $("#table_donasi").DataTable({
        // ketika data masih di muat, tampilkan animasi processing
        // processing: benar
        processing: true,
        // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
        // sisi server: benar
        serverSide: true,
        // lakukan ajax, ke url berikut yang tipe nya adalah dapatkan
        ajax: "/donasi-manual/read",
        // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table donasi    
        // kolom-kolom berisi array, di dalamnya ada object
        columns: [
            // lakukan pengulangan nomor
            // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari donasiController, method index, AddIndexColumn
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                sortable: false
            },
            {
                // nama di dapatkan dari query donasi::select()
                data: 'pendonasi',
                name: 'pendonasi'
            },
            {

                data: 'jumlah_donasi',
                name: 'jumlah_donasi'
            },
            {

                data: 'pesan_donasi',
                name: 'pesan_donasi'
            }
        ],
        // menggunakan bahasa indonesia di package datatables
        // bahasa berisi object
        language: {
            // url memanggil folder public/
            url: "/terjemahan_datatable/indonesia.json"
        }
    });

    // jika #tombol_ubah_periode di click maka jalankan fungsi berikut
    $("#tombol_ubah_periode").on("click", function() {
        // panggil #modal_ubah_periode lalu modalnya ditampilkan
        $("#modal_ubah_periode").modal('show');
    });

    // jika #form_ubah_periode dikirim maka jalankan fungsi berikut dan ambil acara nya
    $("#form_ubah_periode").on("submit", function(e) {
        // event atau acara cegah bawaannya yaitu reload atau muat ulang halaman
        e.preventDefault();

        // panggil .table-responsive lalu hapus element dan anak2x nya
        $('.table-responsive').empty();

        // panggil #tanggal_awal, ambil value nya, pastinya dimulai pada tanggal 1, contohnya 10/01/2023
        let tanggal_awal = $("#tanggal_awal").val();
        // panggil #tanggal_akhir, ambil value nya, berisi tanggal hari ini
        let tanggal_akhir = $("#tanggal_akhir").val();
        // berisi element table sampai th
        let table_baru = `
            <div class="table-responsive">
                <table id="table_donasi" class="table table-striped table-sm table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama Pendonasi</th>
                            <th scope="col">Jumlah Donasi</th>
                            <th scope="col">Pesan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        `;

        // panggil #div_ubah_periode lalu tambahkan value variable table_baru
        $("#div_ubah_periode").append(table_baru);

        // berisi panggil #table_donasi lalu gunakan datatable
        let table = $("#table_donasi").DataTable({
            // ketika data masih di muat, tampilkan animasi proses
            // proses: benar
            processing: true,
            // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, ke route donasi.ubah_periode yang tipe nya adalah kirim
            ajax: {
                // berisi panggil url berikut
                url: "/donasi-manual/ubah-periode",
                // panggil route tipe kirim
                type: "POST",
                // kirimkan data berupa object, aku tidak perlu menulis processData dan kawan-kawan
                data: {
                    // laravel mewajibkan keamanan dari serangan CSRF
                    // kunci _token berisi cetak csrf_token
                    "_token": "{{ csrf_token() }}",
                    // key tanggal_awal berisi value variable tanggal_awal
                    "tanggal_awal": tanggal_awal,
                    "tanggal_akhir": tanggal_akhir
                }
            },
            // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table donasi    
            // kolom-kolom berisi array, di dalamnya ada object
            columns: [
                // lakukan pengulangan nomor
                // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari Controller
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    sortable: false
                },
                {
                    // nama di dapatkan dari query donasi::select()
                    data: 'pendonasi',
                    name: 'pendonasi'
                },
                {

                    data: 'jumlah_donasi',
                    name: 'jumlah_donasi'
                },
                {

                    data: 'pesan_donasi',
                    name: 'pesan_donasi'
                }
            ],
            // menggunakan bahasa indonesia di package datatables
            // bahasa berisi object
            language: {
                // url memanggil folder public/
                url: "/terjemahan_datatable/indonesia.json"
            }
        });
        // lalu panggil #modal_ubah_periode lalu modal nya gua tutup
        $("#modal_ubah_periode").modal("hide");
    });


</script>
@endpush
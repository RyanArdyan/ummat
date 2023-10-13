{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Cek Kebenaran Donasi Manual')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
<style>

.jadikan_pointer {
    cursor: pointer;
}
</style>
@endpush

{{-- kirimkan value @bagian('konten') ke dalam @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            {{-- jika ada sesi status yg isinya sama dengan string berikut --}}
            @if (session('status') === 'Terima kasih sudah mengecek, donasi yang tadi adalah benar')
                {{-- cetak value variable sesi status nya --}}
                <div class="alert alert-success">{{ session('status') }}</div>
            {{-- lain jika value sesi status sama dengan string berikut --}}
            @elseif (session('status') === 'Terima kasih sudah mengecek, donasi yang tadi adalah palsu')
                {{-- cetak value variable sesi status nya --}}
                <div class="alert alert-danger">{{ session('status') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Bukti Donasi</th>
                            <th scope="col">Nama Pendonasi</th>
                            <th scope="col">Jumlah Donasi</th>
                            <th scope="col">Pesan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <script>
        // jika document di click yg class nya adalah class foto_bukti maka jalankan fungsi
        $(document).on("click", ".foto_bukti", function() {
            // berisi panggil .foto_bukti lalu ambil value attribute data-donasi-manual-id
            let donasi_manual_id = $(this).data('donasi-manual-id');
            // pindah ke url berikut
            window.location.href = `/cek-kebenaran-donasi-manual/lihat-foto-bukti/${donasi_manual_id}`;
        });

        // read daftar donasi
        // berisi panggil table donasi, gunakan datatable
        let table = $("table").DataTable({
            // ketika data masih di muat, tampilkan animasi processing
            // processing: benar
            processing: true,
            // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, ke route berikut yang tipe nya adalah dapatkan
            ajax: "{{ route('admin.cek_kebenaran_donasi_manual.read') }}",
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
                    data: 'foto_bukti',
                    name: 'foto_bukti'
                },
                {

                    data: 'nama_pendonasi',
                    name: 'nama_pendonasi'
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

            // panggil #tanggal_awal, ambil value nya
            let tanggal_awal = $("#tanggal_awal").val();
            let tanggal_akhir = $("#tanggal_akhir").val();

            // buat table baru yg akan ditambahkan ke halaman web
            let table_baru = `
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered">
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

            // panggil #div_ubah_periode lalu tambahkan value variable table
            $("#div_ubah_periode").append(table_baru);

            // fitur read daftar donasi berdasarkan periode yg dipilih
            // berisi panggil table lalu gunakan datatable
            let table = $("table").DataTable({
                // ketika data masih di muat, tampilkan animasi proses
                // proses: benar
                processing: true,
                // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
                // sisi server: benar
                serverSide: true,
                // lakukan ajax, ke route admin.donasi.ubah_periode yang tipe nya adalah kirim
                ajax: {
                    // url berisi panggil route berikut
                    url: `donasi/ubah-periode`,
                    // panggil route tipe kirim
                    type: "POST",
                    // kirimkan data dari #form_data, otomatis membuat objek atau {}
                    data: {
                        // key _token berisi cetak method csrf_token milik laravel
                        "_token": "{{ csrf_token() }}",
                        // key tanggal_awal berisi value variable $tanggal_awal
                        "tanggal_awal": tanggal_awal,
                        "tanggal_akhir": tanggal_akhir
                    }
                },
                // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table donasi    
                // kolom-kolom berisi array, di dalamnya ada object
                columns: [
                    // lakukan pengulangan nomor
                    // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari donasiController, method ubah_periode, AddIndexColumn
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        sortable: false
                    },
                    {
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
            // panggil #p_daftar_donasi lalu hapus text nya
            $("#p_daftar_donasi").text('');

            // Menyimpan data dalam sesi
            // sesiPenyimpanan.tetapkanBarang, variable tanggal_awal berisi value variable $tanggal_awal
            sessionStorage.setItem('tanggal_awal', tanggal_awal);
            sessionStorage.setItem('tanggal_akhir', tanggal_akhir);

            // Membaca data dari sesi
            // berisi sesiPenyimpanan.dapatkanBarang dari variable sesi tanggal_awal
            let sesi_tanggal_awal = sessionStorage.getItem('tanggal_awal');
            let sesi_tanggal_akhir = sessionStorage.getItem('tanggal_akhir');

            // cetak value variable sesi_tanggal_awal
            console.log(sesi_tanggal_awal);
            console.log(sesi_tanggal_akhir);
        });

        // Jika #tombol_cetak_pdf di click maka jalankan fungsi berikut:
        $("#tombol_cetak_pdf").on("click", function() {
            // panggil value input name="tanggal_awal"
            // panggil #tanggal_awal lalu ambil value nya
            let tanggal_awal = $("#tanggal_awal").val();
            let tanggal_akhir = $("#tanggal_akhir").val();
    
            // panggil url berikut lalu kirimkan 2 argument
            let url = `/admin/donasi/ekspor_pdf/${tanggal_awal}/${tanggal_akhir}`;

            // panggil #form_cetak_pdf, panggil attribute action lalu isi value nya dengan variable url
            $("#form_cetak_pdf").attr("action", url);
            // panggil #form_cetak_pdf lalu kirim formulir nya
            $("#form_cetak_pdf").submit();
        });
    </script>
@endpush


{{-- ada 2 tanggal_awal dan 2 tanggal_akhir maka apakah nanti kena timpa semoga kena timpa --}}

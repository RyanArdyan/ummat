{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Donasi')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- kirimkan value @bagian('konten') ke dalam @yield('konten')  --}}
@section('konten')
    <div class="row">
        <div class="col-sm-12">
            {{-- jika aku click tombol lakukan donasi maka pindah url dan halaman dengan cara cetak panggil route admin.donasi.create --}}
            <a href="{{ route('admin.donasi.create') }}" class="btn btn-purple btn-sm mb-3">
                <i class="mdi mdi-plus"></i>
                Lakukan donasi
            </a>

            {{-- Jika tombol Ubah Periode di click maka panggil modal ubah periode --}}
            <button id="tombol_ubah_periode" type="button" class="btn btn-purple btn-sm mb-3">
                <i class="mdi mdi-clock-check"></i> Ubah Periode
            </button>

            <form id="form_cetak_pdf" action="" method="POST">
                @csrf
                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                <button id="tombol_cetak_pdf" type="button" class="btn btn-purple btn-sm mb-3">
                    <i class="mdi mdi-clock-check"></i> Cetak PDF
                </button>
            </form>


            <p id="p_daftar_donasi" class="font-weight-bold">Daftar nama pendonasi pada bulan ini.</p>

            <div id="div_ubah_periode">
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
            </div>

            {{-- Fitur hapus beberapa donasi berdasarkan kotak centang yang di checklist --}}
            <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                <i class="mdi mdi-delete"></i>
                Hapus
            </button>

            {{-- termasuk ada jika dipanggil --}}
            {{-- @termasukJika('admin.donasi.modal_ubah_periode') --}}
            @includeIf('admin.donasi.modal_ubah_periode')
        </div>
    </div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
    <script>
        // read daftar donasi
        // berisi panggil table donasi, gunakan datatable
        let table = $("table").DataTable({
            // ketika data masih di muat, tampilkan animasi processing
            // processing: benar
            processing: true,
            // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, ke route admin.donasi.read yang tipe nya adalah dapatkan
            ajax: "{{ route('admin.donasi.read') }}",
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

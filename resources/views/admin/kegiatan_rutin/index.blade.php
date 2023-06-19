{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('titile') --}}
@section('title', 'Kegiatan Rutin')

{{-- kirimkan value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- kirimkan  --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- jika aku click tombol tambah kegiatan maka pindah halaman dengan cara panggil route kegiatan_rutin.create --}}
        <a href="{{ route('kegiatan_rutin.create') }}" class="btn btn-purple btn-sm mb-3">
            <i class="mdi mdi-plus"></i>
            Tambah Kegiatan
        </a>

        <div class="table-responsive">
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column kegiatan_id yang disimpan ke dalam input type="checkbox" --}}
            <form id="form_kegiatan_rutin">
                <!-- laravel mewajibkan keamanan dari serangan csrf -->
                @csrf
                <table class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <!-- Pilih -->
                            <th scope="col" width="5%">
                                {{-- buat kotak centang untuk memilih semua kegiatan_rutin --}}
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th scope="col" width="5%">No</th>
                            <th scope="col" width="22%">Gambar</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
<script>
// tambahkan kedua baris kode berikut agar bilah samping nya runtuh atau sidebar collapse
// panggil element body, lalu tambahkan class berikut  class="enlarged" data-keep-enlarged="true"
$("body").addClass("enlarged");
// panggil element body, lalu tambah attribute data-tetap-membesar
$("body").attr("data-keep-enlarged", "true");

// read daftar kegiatan_rutin
// berisi panggil table kegiatan_rutin, gunakan datatable
let table = $("table").DataTable({
    // ketika data masih di muat, tampilkan animasi processing
    // processing: benar
    processing: true,
    // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
    // sisi server: benar
    serverSide: true,
    // lakukan ajax, ke route kegiatan_rutin.read yang tipe nya adalah dapatkan
    ajax: "{{ route('kegiatan_rutin.read') }}",
    // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table kegiatan, dimana value column tipe_kegiatan nya sama dengan 'Kegiatan Rutin'
    // kolom-kolom berisi array, di dalamnya ada object
    columns: [
        // kotak centang
        {
            // select di dapatkan dari AddColumn('select') milik KegiatanRutinController, method read
            data: "select",
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false
        },
        // lakukan pengulangan nomor
        // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari KegiatanRutinController, method index, AddIndexColumn
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            sortable: false
        },
        {
            // gambar_kegiatan di dapatkan dari AddColumn('gambar_kegiatan') milik KegiatanRutinController, method read
            data: "gambar_kegiatan",
        },
        {
            // nama di dapatkan dari query Kegiatan::select()
            data: 'nama_kegiatan',
            name: 'nama_kegiatan'
        },
        {
            // tanggal di dapatkan dari AddColumn('tanggal') milik KegiatanRutinController, method read
            data: 'tanggal',
            name: 'tanggal'
        },
        {
            // jam_mulai di dapatkan dari query Kegiatan::select()
            data: 'jam_mulai',
            name: 'jam_mulai'
        },
        {
            // jam_selesai di dapatkan dari query Kegiatan::select()
            data: 'jam_selesai',
            name: 'jam_selesai'
        },
        {
            // action di dapatkan dari AddColumn('action') milik KegiatanRutinController, method read
            data: 'action',
            name: 'action',
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false,
            // dapatDicari: salah berarti tulisan edit pada column action tidak dapat di cari
            searchable: false
        }
    ],
    // menggunakan bahasa indonesia di package datatables
    // bahasa berisi object
    language: {
        // url memanggil folder public/
        url: "/terjemahan_datatable/indonesia.json"
    }
});
</script>
@endpush
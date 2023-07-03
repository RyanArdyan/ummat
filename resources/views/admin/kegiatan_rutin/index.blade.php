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
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column kegiatan_rutin_id yang disimpan ke dalam input type="checkbox" --}}
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
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>

        {{-- Fitur hapus beberapa kegiatan_rutin berdasarkan kotak centang yang di checklist --}}
        <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm mt-3">
            <i class="mdi mdi-delete"></i>
            Hapus
        </button>
    </div>
</div>
@endsection

{{-- dorong value @dorong('script') ke @stack('script') --}}
@push('script')
<script>
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
            // hari di dapatkan dari AddColumn('hari') milik KegiatanRutinController, method read
            data: 'hari',
            name: 'hari'
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

// jika #pilih_semua di click maka jalankan fungsi berikut
$("#select_all").on("click", function() {
    // jika #pilih_semua di centang maka
    if ($("#select_all").prop("checked")) {
        // panggil .pilih lalu centang nya benar
        $(".pilih").prop("checked", true);
    } 
    // jika #pilih_semua tidak di centang maka
    else {
        // panggil .pilih lalu centang nya dihapus atau salah
        $(".pilih").prop("checked", false);
    };
});

// Delete atau hapus
// jika #tombol_hapus di click maka jalankan fungsi berikut
$("#tombol_hapus").on("click", function() {
    // jika input .pilih yang di centang di KegiatanRutinController, panjang nya sama dengan 0 maka
    if ($("input.pilih:checked").length === 0) {
        // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
        Swal.fire('Anda belum memilih kegiatan rutin.');
    }
    // jika input .pilih yang di centang di KegiatanRutinController, panjang nya lebih atau sama dengan 1 maka
    else if ($("input.pilih:checked").length >= 1) {
        // tampilkan konfirmasi penghapusan menggunakan sweetalert
        Swal.fire({
            // judul: 
            title: 'Apakah anda yakin?',
            // text: 
            text: "Anda tidak akan dapat mengembalikan ini!",
            // ikon: 
            icon: 'warning',
            // tampilkanTombolBatal
            showCancelButton: true,
            // warnaTombolKonfirmasi
            confirmButtonColor: '#3085d6',
            // warnaTombolBatal
            cancelButtonColor: '#d33',
            // textTombolKonfirmasi
            confirmButtonText: 'Ya, hapus!'
        })
        // kmeudian hasilnya, jalankan fungsi berikut, parameter result
        .then((result) => {
            // jika hasilnya di konfirmasi
            if (result.isConfirmed) {
                // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form 
                // sebenarnya aku mengirim beberapa value input name="pengeluaran_ids" yang di centang
                // jquery lakukan ajax tipe kirim, panggil route kegiatan_rutin.destroy, panggil #form_kegiatan_rutin, kirimkan value input
                $.post("{{ route('kegiatan_rutin.destroy') }}", $("#form_kegiatan_rutin").serialize())
                    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                    .done(function(resp) {
                        // notifkasi menggunakan sweetalert
                        Swal.fire(
                            'Dihapus!',
                            'Berhasil menghapus kegiatan_rutin yang dipilih.',
                            'success'
                        );
                        // reload ajax table
                        table.ajax.reload();
                    });
            };
        });
    };
});
</script>
@endpush
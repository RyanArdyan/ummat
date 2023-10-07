{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Kegiatan Sekali')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- @php agar bisa menulis kode php di .blade --}}
@php
    
@endphp

{{-- kirimkan  --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- jika aku click tombol tambah kegiatan maka pindah halaman dengan cara panggil route kegiatan_sekali.create --}}
        <a href="{{ route('admin.kegiatan_sekali.create') }}" class="btn btn-purple btn-sm mb-3">
            <i class="mdi mdi-plus"></i>
            Tambah Kegiatan
        </a>


        <div class="table-responsive">
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column kegiatan_sekali_id yang disimpan ke dalam input type="checkbox" --}}
            <form id="form_kegiatan_sekali">
                <!-- laravel mewajibkan keamanan dari serangan csrf -->
                @csrf
                <table class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <!-- Pilih atau kotak centang -->
                            <th scope="col" width="5%">
                                {{-- buat kotak centang untuk memilih semua kegiatan_sekali --}}
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th scope="col" width="5%">No</th>
                            <th scope="col" width="22%">Gambar</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Mulai (WIB)</th>
                            <th scope="col">Selesai (WIB)</th>
                            <th scope="col">Action</th>

                            {{-- HAPUS SAJA JAM NYA AGAR TIDAK LEBIH DAN EFISIEN --}}
                        </tr>
                    </thead>
                </table>
            </form>
        </div>

        

        {{-- Fitur hapus beberapa kegiatan_sekali berdasarkan kotak centang yang di checklist --}}
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

// read daftar kegiatan_sekali
// berisi panggil table kegiatan_sekali, gunakan datatable
let table = $("table").DataTable({
    // ketika data masih di muat, tampilkan animasi processing
    // processing: benar
    processing: true,
    // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
    // sisi server: benar
    serverSide: true,
    // lakukan ajax, ke route kegiatan_sekali.read yang tipe nya adalah dapatkan
    ajax: "{{ route('admin.kegiatan_sekali.read') }}",
    // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table kegiatan, dimana value column tipe_kegiatan nya sama dengan 'Kegiatan sekali'
    // kolom-kolom berisi array, di dalamnya ada object
    columns: [
        // kotak centang
        {
            // select di dapatkan dari AddColumn('select') milik KegiatanSekaliController, method read
            data: "select",
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false
        },
        // lakukan pengulangan nomor
        // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari KegiatanSekaliController, method index, AddIndexColumn
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            sortable: false
        },
        {
            // gambar_kegiatan di dapatkan dari AddColumn('gambar_kegiatan') milik KegiatanSekaliController, method read
            data: "gambar_kegiatan",
        },
        {
            // nama di dapatkan dari query Kegiatan::select()
            data: 'nama_kegiatan',
            name: 'nama_kegiatan'
        },
        {
            // tanggal di dapatkan dari AddColumn('tanggal') milik KegiatanSekaliController, method read
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
            // action di dapatkan dari AddColumn('action') milik KegiatanSekaliController, method read
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


// pilih semua
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
    // jika input .pilih yang di centang di KegiatanSekaliController, panjang nya sama dengan 0 maka
    if ($("input.pilih:checked").length === 0) {
        // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
        Swal.fire('Anda belum memilih kegiatan sekali.');
    }
    // jika input .pilih yang di centang di KegiatanSekaliController, panjang nya lebih atau sama dengan 1 maka
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
                // jquery lakukan ajax tipe kirim, panggil route kegiatan_sekali.destroy, panggil #form_kegiatan_sekali, kirimkan value input
                $.post("{{ route('admin.kegiatan_sekali.destroy') }}", $("#form_kegiatan_sekali").serialize())
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                .done(function(resp) {
                    // notifkasi menggunakan sweetalert
                    Swal.fire(
                        'Dihapus!',
                        'Berhasil menghapus kegiatan sekali yang dipilih.',
                        'success'
                    );
                    // reload ajax table
                    // panggil value variable table, lalu ajax nya di muat ulang
                    table.ajax.reload();
                });
            };
        });
    };
});
</script>
@endpush



{{-- Aku ingin mengubah admin/kegiatan_sekali/index, th jam_mulai, jam_selesai aku tambah (WIB) --}}
{{-- memperluas parent nya yaitu admin.layouts.app --}}
@extends('admin.layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Postingan')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush




{{-- kirimkan value @bagian('konten') ke dalam @yield('konten')  --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- jika aku click tombol tambah Postingan maka pindah url dan halaman dengan cara cetak panggil route postingan.create --}}
        <a href="{{ route('admin.postingan.create') }}" class="btn btn-purple btn-sm mb-3">
            <i class="mdi mdi-plus"></i>
            Tambah Postingan
        </a>

        <div class="table-responsive">
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column postingan_id yang disimpan ke dalam input type="checkbox" --}}
            <form id="form_postingan">
                <!-- laravel mewajibkan keamanan dari serangan csrf -->
                @csrf
                <table class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <!-- Pilih atau kotak centang -->
                            <th scope="col" width="5%">
                                {{-- buat kotak centang untuk memilih semua postingan --}}
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th scope="col" width="5%">No</th>
                            <th scope="col" width="22%">Gambar</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Di publikasi pada</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>

        

        {{-- Fitur hapus beberapa postingan berdasarkan kotak centang yang di checklist --}}
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

// read daftar postingan
// berisi panggil table postingan, gunakan datatable
let table = $("table").DataTable({
    // ketika data masih di muat, tampilkan animasi processing
    // processing: benar
    processing: true,
    // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
    // sisi server: benar
    serverSide: true,
    // lakukan ajax, ke route postingan.read yang tipe nya adalah dapatkan
    ajax: "{{ route('admin.postingan.read') }}",
    // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table postingan    
    // kolom-kolom berisi array, di dalamnya ada object
    columns: [
        // kotak centang
        {
            // select di dapatkan dari AddColumn('select') milik PostinganController, method read
            data: "select",
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false
        },
        // lakukan pengulangan nomor
        // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari PostinganController, method index, AddIndexColumn
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            sortable: false
        },
        {
            // gambar_postingan di dapatkan dari AddColumn('gambar_postingan') milik PostinganController, method read
            data: "gambar_postingan",
        },
        {
            // nama di dapatkan dari query postingan::select()
            data: 'judul_postingan',
            name: 'judul_postingan'
        },
        {
            
            data: 'penulis',
            name: 'penulis'
        },
        {
            data: 'kategori',
            name: 'kategori'
        },
        {
            data: 'dipublikasi_pada',
            name: 'dipublikasi_pada'
        },
        {
            // action di dapatkan dari AddColumn('action') milik PostinganController, method read
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


// berarti lain jika resp.errors.kategori_id sama dengan "Input kategori id harus diisi."


// Delete atau hapus
// jika #tombol_hapus di click maka jalankan fungsi berikut
$("#tombol_hapus").on("click", function() {
    // jika input .pilih yang di centang di PostinganController, method read, panjang nya sama dengan 0 maka
    if ($("input.pilih:checked").length === 0) {
        // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
        Swal.fire('Anda belum memilih postingan.');
    }
    // lain jika input .pilih yang di centang di PostinganController, panjang nya lebih atau sama dengan 1 maka
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
                // sebenarnya aku mengirim beberapa value input name="postingan_ids" yang di centang
                // jquery lakukan ajax tipe kirim, panggil route postingan.destroy, panggil #form_postingan, kirimkan value input
                $.post("{{ route('admin.postingan.destroy') }}", $("#form_postingan").serialize())
                    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                    .done(function(resp) {
                        // notifkasi menggunakan sweetalert
                        Swal.fire(
                            'Dihapus!',
                            'Berhasil menghapus postingan yang dipilih.',
                            'success'
                        );
                        // reload ajax tablez
                        // panggil value variable table, lalu ajax nya di muat ulang
                        table.ajax.reload();
                    })
            };
        });
    };
});
</script>
@endpush
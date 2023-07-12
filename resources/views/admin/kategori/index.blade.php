{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Kategori')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- kirimkan value @bagian('konten') lalu nanti ditangkap @yield('konten')  --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- jika aku click tombol tambah kategori maka pindah url dan halaman dengan cara panggil route kategori.create --}}
        <a href="{{ route('kategori.create') }}" class="btn btn-purple btn-sm mb-3">
            <i class="mdi mdi-plus"></i>
            Tambah kategori
        </a>

        <div class="table-responsive">
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column kategori_id yang disimpan ke dalam input type="checkbox" --}}
            <form id="form_kategori">
                <!-- laravel mewajibkan keamanan dari serangan csrf -->
                @csrf
                <table class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <!-- Pilih atau kotak centang -->
                            <th scope="col" width="5%">
                                {{-- buat kotak centang untuk memilih semua kategori --}}
                                <input type="checkbox" name="pilih_semua" id="pilih_semua">
                            </th>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>

        {{-- Fitur hapus beberapa kategori berdasarkan kotak centang yang di checklist --}}
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
// read daftar kategori
// berisi panggil table kategori, gunakan datatable
let table = $("table").DataTable({
    // ketika data masih di muat, tampilkan animasi processing
    // processing: benar
    processing: true,
    // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
    // sisi server: benar
    serverSide: true,
    // lakukan ajax, cetak ke route kategori.read yang tipe nya adalah dapatkan
    ajax: "{{ route('kategori.read') }}",
    // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table kategori
    // kolom-kolom berisi array, di dalamnya ada object
    columns: [
        // kotak centang
        {
            // select di dapatkan dari rawColumns('select') milik KategoriController, method read
            data: "select",
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false
        },
        // lakukan pengulangan nomor
        // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari KategoriController, method index, AddIndexColumn
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            // Menonaktifkan fitur anak panah atau fitur balikkan data
            sortable: false
        },
        {
            // nama di dapatkan dari query kategori::select()
            data: 'nama_kategori',
            name: 'nama_kategori'
        },
        {
            // action di dapatkan dari AddColumn('action') milik KategoriController, method read
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
$("#pilih_semua").on("click", function() {
    // jika #pilih_semua di centang maka
    if ($("#pilih_semua").prop("checked")) {
        // panggil .pilih yang dibuat di KategoriController, method read, lalu centang nya benar
        $(".pilih").prop("checked", true);
    } 
    // jika #pilih_semua tidak di centang maka
    else if (!$("#pilih_semua").prop("checked")) {
        // panggil .pilih lalu centang nya dihapus atau salah
        $(".pilih").prop("checked", false);
    };
});


// Delete atau hapus
// jika #tombol_hapus di click maka jalankan fungsi berikut
$("#tombol_hapus").on("click", function() {
    // jika input .pilih yang di centang di KategoriController, method read, bagian select, panjang nya sama dengan 0 maka
    if ($("input.pilih:checked").length === 0) {
        // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
        Swal.fire('Anda belum memilih baris kategori untuk dihapus.');
    }
    // lain jika input .pilih yang di centang di KategoriController, method read, bagian select, panjang nya lebih atau sama dengan 1 maka
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
                // .serialize() berarti aku mengirim beberapa value input name="kategori_ids" yang di centang karena aku menyimpan table dan data nya di dalam form
                // jquery lakukan ajax tipe kirim, panggil route kategori.destroy, panggil #form_kategori, kirimkan value input name="kategori_ids" atau #form_kategori, membuat cerita bersambung
                $.post("{{ route('kategori.destroy') }}", $("#form_kategori").serialize())
                    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                    .done(function(resp) {
                        // notifkasi menggunakan sweetalert
                        Swal.fire(
                            'Dihapus!',
                            'Berhasil menghapus kategori yang dipilih.',
                            'success'
                        );
                        // reload ajax table
                        // panggil value variable table, lalu ajax nya di muat ulang
                        table.ajax.reload();
                    })
                    // jika gagal karena ada kategori yang digunakan oleh postingan atau ada value kategori_id di table postingan_kategori, column kategori_id
                    .fail(function() {
                        // tampilkan notifikasi menggunakan package sweetalert
                        Swal.fire(
                            "Tidak dapat menghapus karena ada kategori yang masih digunakan di postingan"
                        );
                    });
            };
        });
    };
});

// jika .tombol_detail_kategori di click maka jalankan fungsi
// alasan pake $(document) adalah karena tombol detail dibuat oleh controller atau lebih tepat nya script
$(document).on("click", ".tombol_detail_kategori", function() {
    // ambil value attribute data-kategori-id
    // panggil .tombol_detail_kategori, lalu cetak value attribute data- xkategori-id
    let kategori_id = $(this).data("kategori-id");
    // jquery lakukan ajax untuk mengambil detail_kategori
    $.ajax({
        // url panggil url /kategori lalu kirimkan value variable kategori_id
        // tanda backtiq (``) bisa mencetak value variable di dalam string menggunakan ${}
        url: `/kategori/${kategori_id}`,
        type: 'GET',
    })
    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
    .done(function(resp) {
        // panggil #nama_kategori lalu text nya diisi tanggapan.detaiL_kategori.nama_kategori
        $("#nama_kategori").text(resp.detail_kategori.nama_kategori);
        // panggil #bacaan_arab lalu text nya diisi tanggapan.detaiL_kategori.bacaan_arab
        $("#bacaan_arab").text(resp.detail_kategori.bacaan_arab);
        // panggil #bacaan_latin lalu text nya diisi tanggapan.detaiL_kategori.bacaan_latin
        $("#bacaan_latin").text(resp.detail_kategori.bacaan_latin);
        // panggil #arti_kategorinya lalu text nya diisi tanggapan.detaiL_kategori.arti_kategorinya
        $("#arti_kategorinya").text(`Artinya: ${resp.detail_kategori.arti_kategorinya}`);
    });
});
</script>
@endpush
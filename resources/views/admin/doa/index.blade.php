{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title lalu ditangkap oleh @yield('title') --}}
@section('title', 'Doa')

{{-- Dorong value @dorong('css') ke @stack('css') --}}
@push('css')
@endpush

{{-- kirimkan value @bagian('konten') lalu nanti ditangkap @stack('konten')  --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        {{-- jika aku click tombol tambah Doa maka pindah url dan halaman dengan cara panggil route doa.create --}}
        <a href="{{ route('doa.create') }}" class="btn btn-purple btn-sm mb-3">
            <i class="mdi mdi-plus"></i>
            Tambah Doa
        </a>

        {{-- termasuk jika admin.doa.modal_detail di panggil --}}
        @includeIf('admin.doa.modal_detail')

        <div class="table-responsive">
            {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column doa_id yang disimpan ke dalam input type="checkbox" --}}
            <form id="form_doa">
                <!-- laravel mewajibkan keamanan dari serangan csrf -->
                @csrf
                <table id="table_doa" class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <!-- Pilih atau kotak centang -->
                            <th scope="col" width="5%">
                                {{-- buat kotak centang untuk memilih semua doa --}}
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama Doa</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                </table>
                
            </form>
        </div>

        

        {{-- Fitur hapus beberapa doa berdasarkan kotak centang yang di checklist --}}
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
// read daftar doa
// berisi panggil table doa, gunakan datatable
let table = $("table").DataTable({
    // ketika data masih di muat, tampilkan animasi processing
    // processing: benar
    processing: true,
    // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
    // sisi server: benar
    serverSide: true,
    // lakukan ajax, ke route doa.read yang tipe nya adalah dapatkan
    ajax: "{{ route('doa.read') }}",
    // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table doa
    // kolom-kolom berisi array, di dalamnya ada object
    columns: [
        // kotak centang
        {
            // select di dapatkan dari rawColumns('select') milik DoaController, method read
            data: "select",
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false
        },
        // lakukan pengulangan nomor
        // DT_RowIndex di dapatkan dari laravel datatable atau di dapatkan dari DoaController, method index, AddIndexColumn
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            sortable: false
        },
        {
            // nama di dapatkan dari query doa::select()
            data: 'nama_doa',
            name: 'nama_doa'
        },
        {
            // action di dapatkan dari AddColumn('action') milik DoaController, method read
            data: 'action',
            name: 'action',
            // menonaktifkan fungsi icon anak panah atau fitur balikkan data
            sortable: false,
            // dapatDicari: salah berarti tulisan detail dan edit pada column action tidak dapat di cari
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
    // jika input .pilih yang di centang di DoaController, panjang nya sama dengan 0 maka
    if ($("input.pilih:checked").length === 0) {
        // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
        Swal.fire('Anda belum memilih baris doa untuk dihapus.');
    }
    // jika input .pilih yang di centang di DoaController, panjang nya lebih atau sama dengan 1 maka
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
                // .serialize() berarti aku mengirim beberapa value input name="pengeluaran_ids" yang di centang karena aku menyimpan table dan data nya di dalam form
                // jquery lakukan ajax tipe kirim, panggil route doa.destroy, panggil #form_doa, kirimkan value input
                $.post("{{ route('doa.destroy') }}", $("#form_doa").serialize())
                    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                    .done(function(resp) {
                        // notifkasi menggunakan sweetalert
                        Swal.fire(
                            'Dihapus!',
                            'Berhasil menghapus doa yang dipilih.',
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

// jika .tombol_detail_doa di click maka jalankan fungsi
// alasan pake $(document) adalah karena tombol detail dibuat oleh controller atau lebih tepat nya script
$(document).on("click", ".tombol_detail_doa", function() {
    // ambil value attribute data-doa-id
    // panggil .tombol_detail_doa, lalu cetak value attribute data-doa-id
    let doa_id = $(this).data("doa-id");
    // jquery lakukan ajax untuk mengambil detail_doa
    $.ajax({
        // panggil url /doa lalu kirimkan value variable doa_id
        // tanda backtiq (``) bisa mencetak value variable di dalam string menggunakan ${}
        url: `/doa/${doa_id}`,
        // panggil route tipe get
        type: 'GET',
    })
    // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
    .done(function(resp) {
        // cetak value resp.detail_doa
        console.log(resp.detail_doa);
        // panggil #nama_doa lalu text nya diisi tanggapan.detaiL_doa.nama_doa
        $("#nama_doa").text(resp.detail_doa.nama_doa);
        // panggil #bacaan_arab lalu text nya diisi tanggapan.detaiL_doa.bacaan_arab
        $("#bacaan_arab").text(resp.detail_doa.bacaan_arab);
        // panggil #bacaan_latin lalu text nya diisi tanggapan.detaiL_doa.bacaan_latin
        $("#bacaan_latin").text(resp.detail_doa.bacaan_latin);
        // panggil #arti_doanya lalu text nya diisi tanggapan.detaiL_doa.arti_doanya
        $("#arti_doanya").text(`Artinya: ${resp.detail_doa.arti_doanya}`);
    });
});
</script>
@endpush
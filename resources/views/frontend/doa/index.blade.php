{{-- @memperluas('frontend/layouts/app') --}}
@extends('frontend.layouts.app')

{{-- Kirimkan value @bagian('title') ke @yield('title') --}}
@section('title', 'Doa')

{{-- kirimkan value @bagian('konten') ke @yield('konten') --}}
@section('konten')
    <!-- carousel -->
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.carousel')

    <div class="container">
        <h1 class="mt-4 text-center">Doa</h1>

        {{-- termasuk ada jika dipanggil --}}
        {{-- @termasukJika --}}
        @include('frontend.doa.modal_detail')

        {{-- Table Doa --}}
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Doa</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection


{{-- Untuk mengakses detail doa, user tidak perlu login --}}

{{-- @dorong value script ke @stack script --}}
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
            // lakukan ajax, ke route frontend.doa.read yang tipe nya adalah dapatkan
            ajax: "{{ route('frontend.doa.read') }}",
            // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data table doa
            // kolom-kolom berisi array, di dalamnya ada object
            columns: [
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

        // jika document di click yang class nya adalah .tombol_detail_doa di click maka jalankan fungsi
        $(document).on("click", ".tombol_detail_doa",  function() {
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
                // panggil #nama_doa lalu text nya diisi tanggapan.detaiL_doa.nama_doa
                $("#nama_doa").text(resp.detail_doa.nama_doa);
                $("#bacaan_arab").text(resp.detail_doa.bacaan_arab);
                $("#bacaan_latin").text(resp.detail_doa.bacaan_latin);
                // panggil #arti_doanya lalu text nya diisi tanggapan.detaiL_doa.arti_doanya
                $("#arti_doanya").text(`Artinya: ${resp.detail_doa.arti_doanya}`);

                // panggil modal lalu tampilkan
                $(".modal_detail_doa").modal("show");
            });
        });
    </script>
@endpush
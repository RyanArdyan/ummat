{{-- modal ubah periode --}}
<div class="modal fade" id="modal_ubah_periode" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="label_latar_belakang_statis" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="form_ubah_periode">
            {{-- laravel mewajibkan keamanan dari serangan csrf --}}
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="label_latar_belakang_statis">Ubah Periode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{-- aku butuh .is-invalid untuk menampilkan efek validasi error --}}
                {{-- tanggal_awal --}}
                <div class="form-group">
                    <label for="tanggal_awal">Tanggal awal (bulan/tanggal/tahun)<span class="text-danger"> *</span></label>
                    {{-- attribute value akan mencetak tanggal awal --}}
                    <input id="tanggal_awal" name="tanggal_awal" value="{{ $tanggal_awal }}" class="form-control"
                        type="date" required>
                </div>
                {{-- is-invalid --}}
                {{-- tanggal akhir --}}
                <div class="form-group">
                    <label for="tanggal_akhir">Tanggal akhir (bulan/tanggal/tahun)<span class="text-danger"> *</span></label>
                    {{-- attribute value akan mencetak tanggal akhir --}}
                    <input id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_hari_ini }}" class="form-control"
                        type="date" required>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                        class="mdi mdi-close"></i> Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="mdi mdi-clock-check"></i> Ubah
                    Periode</button>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>


{{-- coba cetak tanggal awal dan tanggal akhir menggunakan controller --}}
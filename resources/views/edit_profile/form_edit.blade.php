<div class="container mt-3">
    {{-- enctype digunakan jika ada input type file --}}
    <form id="perbarui_profile" enctype="multipart/form-data">
        @csrf
        {{-- ID --}}
        <div class="mb-3">
            <label class="form-label" for="id" hidden>ID</label>
            {{-- cetak value column user_id yang login ke dalam attribute value --}}
            {{-- aku butuh user_id, agar bisa update detail user --}}
            {{-- aku butuh name untuk mengirim value input --}}
            <input name="id_user" type="number" class="form-control" id="id" placeholder="Edit id"
                value="{{ $detail_user_yang_login->user_id }}" readonly>
        </div>

        {{-- name --}}
        <div class="mb-3">
            <label class="form-label" for="nama">Nama</label>
            <input name="name" type="text" class="input input_name form-control" id="nama"
                placeholder="Edit Nama" value="{{ $detail_user_yang_login->name }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_name text-danger"></span>
        </div>

        {{-- nik --}}
        <div class="mb-3">
            <label class="form-label" for="nik">NIK</label>
            <input name="nik" type="number" class="input input_nik form-control" id="nik"
                placeholder="Edit Nik" value="{{ $detail_user_yang_login->nik }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_nik text-danger"></span>
        </div>

        {{-- nomor_wa --}}
        <div class="mb-3">
            <label class="form-label" for="nomor_wa">Nomor Whatsapp</label>
            {{-- ketika keyboard ditekan maka kembalikkan, lalu panggil fungsi number lalu kirimkan acara sebagai argument --}}
            <input name="nomor_wa" type="text" class="input input_nomor_wa form-control" id="nomor_wa"
                placeholder="Edit Nomor Whatsapp" value="{{ $detail_user_yang_login->nomor_wa }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_nomor_wa text-danger"></span>
        </div>

        {{-- tgl_lahir --}}
        <div class="mb-3">
            <label class="form-label" for="tgl_lahir">Tanggal Lahir</label>
            <input name="tgl_lahir" type="date" class="input input_tgl_lahir form-control" id="tgl_lahir" value="{{ $detail_user_yang_login->tgl_lahir }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_tgl_lahir text-danger"></span>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="mb-3">
            <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                {{-- value dari attribute value akan masuk ke controller dan column jenis_kelamin di table users --}}
                {{-- jika value $detail_user_yang_login, calumn jenis_kelamin sama dengan "laki-laki" maka tambahkan attribute selected, kalau bukan maka kosongkan --}}
                <option {{ ($detail_user_yang_login->jenis_kelamin === "laki-laki") ? "selected" : "" }} value="laki-laki">Laki-laki</option>
                {{-- value dari attribute value akan masuk ke controller dan column jenis_kelamin di table users --}}
                {{-- jika value $detail_user_yang_login, calumn jenis_kelamin sama dengan "perempuan" maka tambahkan attribute selected, kalau bukan maka kosongkan --}}
                <option {{ ($detail_user_yang_login->jenis_kelamin === "perempuan") ? "selected" : "" }} value="perempuan">Perempuan</option>
            </select>
        </div>

        {{-- Foto Profile --}}
        <div class="mb-3">
            <label class="form-label" for="pilih_foto">Foto Profil</label>
            <br>
            {{-- asset akan memanggil folder public --}}
            {{-- cetak value detail_user_yang_login, column foto --}}
            <img id="pratinjau_foto" src="{{ asset('storage/foto_profil') }}/{{ $detail_user_yang_login->foto }}"
                alt="foto User" width="150px" height="150px" class="mb-3 rounded">
            <br>
            <input name="foto" type="file" class="input input_foto form-con" id="pilih_foto">
            {{-- pesan error --}}
            <span class="error error_foto text-danger"></span>
        </div>

        {{-- Update Passowrd --}}
        {{-- javascript:void(0) sama seperti # hanya saja dia tidak akan kembali ke halaman atas --}}
        {{-- cetak value detail_user_yang_login kolom user_id --}}
        <button data-bs-toggle="modal" data-bs-target="#modal_edit_password" type="button" id="edit_password" data-id="{{ $detail_user_yang_login->user_id }}" class="btn btn-primary ml-1"> Edit Password?</button>
        <br>

        <button type="submit" class="btn btn-primary mt-2">
            <i class="mdi mdi-update"></i>
            Perbarui
        </button>
    </form>
</div>

{{-- karena ada attribute data-target mmaka harusnya tak perlu script untuk panggil moadl, co0ba buyka form modal nya --}}
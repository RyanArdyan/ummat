{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan valuenya ke @yield('title') --}}
@section('title', 'Edit Profile')

{{-- kirimkan valuenya ke @yield('konten') --}}
@section('konten')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                {{-- Jika ada value dari session status yg dikirimkan dari Middleware/ProfileSudahLengkap.php --}}
                @if (session('status'))
                    {{-- cetak value session('status') --}}
                    <div class="alert alert-danger">{{ session('status') }}</div>
                @endif

                {{-- @termasuk formulir edit data --}}
                @include('edit_profile.form_edit')
                {{-- modal edit password --}}
                @include('edit_profile.modal_edit_password')
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@push('script')
<script>
    // jika #nomor_wa di masukkan karakter maka jalankan fungsi
    $("#nomor_wa").on("input", function() {
        /// Mengganti semua karakter kecuali angka dan spasi dengan string kosong
        var sanitizedInput = $(this).val().replace(/[^0-9\s]/g, '');

        // Memastikan tidak ada spasi berulang
        var formattedInput = sanitizedInput.replace(/\s+/g, ' ');

        // Mengatur nilai input dengan hasil format
        $(this).val(formattedInput);
    });

    // tampilkan pratinjau foto ketika user mengubah foto
    // jika #pilih_foto diubah maka jalankan fungsi berikut
    $("#pilih_foto").on("change", function() {
        // ambil fotonya, this berarti @pilih_gamabr
        let foto = this.files[0];
        // jika ada foto yang di pilih
        if (foto) {
            // berisi baru FilePembaca
            let filePembaca = new FileReader();
            // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
            filePembaca.onload = function(e) {
                // panggil #pratinjau_foto lalu pangil attribute src diisi dengan acara.target.hasil
                $("#pratinjau_foto").attr("src", e.target.result);
            };
            // new FileReader() baca data sebagai url dari this.file[0]
            filePembaca.readAsDataURL(foto);
        };
    });

    // jika #perbarui_profil dikirim maka jalankan fungsi berikut dan tangkap eventnya
    $("#perbarui_profile").on("submit", function(e) {
        // cegah bawaanya yaitu reload
        // acara.cegahBawaan()
        e.preventDefault();
        // lakukan ajax
        $.ajax({
            // ke route update_profile
            url: `{{ route('update_profile') }}`,
            // panggil route type post
            type: "POST",
            // kirimkan data formulir atau input2x dari #perbarui_profile
            data: new FormData(this),
            // aku butuh 3 baris kode dibawah kecuali jika aku mengirimkan data menggunakan object manual
            processData: false,
            contentType: false,
            cache: false,
            // menghapus validasi error sebelum form dikirim agar ada fitur refresh validasi error
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .input lalu hapus .is-invalid
                $(".input").removeClass("is-invalid");
                // panggil .error lalu kosongkan textnya
                $(".error").text("");
            }
        })
        // jika selesai maka jalankan fungsi berikut
        .done(function(resp) {
            // jika validasi menemukan error
            // jika (value tanggapan.status sama dengan 0) maka
            if (resp.status === 0) {
                // lakukan pengulangan, key berisi tiap value attribute name
                // value berisi semua pesan error
                // jquery.setiap(value tanggapan.kesalahan2x, fungsi(kunci, nilai))
                $.each(resp.errors, function(key, value) {
                    // anggapalh panggil .input_name lalu tambahkan .is-invalid
                    $(`.input_${key}`).addClass('is-invalid');
                    // anggapalh panggil .error_name lalu text nya diisi value parameter value
                    $(`.error_${key}`).text(value);
                });
            } 
            // lain jika berhasil memperbarui profll
            // lain jika (value tanggapan.status sama dengan 200)
            else if (resp.status === 200) {
                // perbarui nama user di layouts/top-navbar dan layouts/left-side
                // panggil .nama_user lalu text nya diisi value tanggapan.detail_name
                $(".nama_user").text(resp.detail_name);
                // perbarui foto profil di layouts/top-navbar dan layouts/left-side
                // panggil .foto_profil lalu pangil attribute src lalu isi dengan /storage/foto_profil/ value tanggapan.detail_foto
                $(".foto_profil").attr("src", `/storage/foto_profil/${resp.detail_foto}`);
                // notifikasi menggunakan sweetalert 2
                Swal.fire(
                    'Berhasil',
                    'Profile Berhasil Diperbarui',
                    'success'
                );
                // panggil .alert-danger lalu hapus
                $(".alert-danger").remove();
            };
        });
    });

    // jika .lihat_password di click maka jalankan fungsi berikut
    $(".lihat_password").on("click", function() {
        // jika .lihat_passwored memiliki .fa-eye
        if ($(this).hasClass("fa-eye")) {
            // .ubah_type_password panggil attribute type lalu ubah menjadi type text
            $(".ubah_type_password").attr("type", "text");
            // .lihat_password, textnya menjadi sembunyikan password
            $(this).text('Sembunyikan Password');
            // .lihat_password, hapus class fa-eye
            $(this).removeClass("fa-eye");
            // .lihat_password tambah .fa-eye-slash
            $(this).addClass("fa-eye-slash");
        }
        // lain jika .lihat_passwored memiliki .fa-eye-slash
        else if ($(this).hasClass('fa-eye-slash')) {
            // .ubah_type_password panggil attribute type lalu ubah menjadi type text
            $(".ubah_type_password").attr("type", "password");
            // .lihat_password, ubah teks nya menjadi lihat_password
            $(this).text('Lihat Password');
            // .lihat_password hapus .fa-eye.slash
            $(this).removeClass("fa-eye-slash");
            // .lihat_password tambah class .fa-eye
            $(this).addClass("fa-eye");
            // logikanya sama saja, hanya saja terbalik secara kode 
        };
    });

    // Update password
    // jika #form_edit_password dikirim maka jalankan fungsi berikut
    $("#form_edit_password").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();
        // lakukan ajax
        $.ajax({
            // panggil route edit_profile.perbarui_password
            url: `{{ route('edit_profile.update_password') }}`,
            // panggil route type post
            type: "POST",
            // kirimkan data formulir atau data input2x
            // data berisi baru FormulirData(ini)
            data: new FormData(this),
            // 3 baris kode dibawah ini wajib
            processData: false,
            contentType: false,
            cache: false,
            // sebelum kirim, hapus validasi error
            // sebelum kirim, jalankan fungsi berikut agar ada efek refresh validasi errr
            beforeSend: function() {
                // panggil .e_input lalu hapus .is-invalid, defaultnya adalah tidak ada
                $(".e_input").removeClass("is-invalid");
                // panggil .e_pesan_error lalu kosongkan textnya
                $(".e_pesan_error").text("");
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut sambil mengambil response
        .done(function(resp) {
            // jika validasi error
            if (resp.status === 0) {
                // aku melakukan pengulangan untuk error
                // key berisi setiap value attribute name dan value berisi pesan errornya
                $.each(resp.errors, function(key, value) {
                    // contohnya panggil .e_password_lama_input lalu tambah .is-invalid
                    $(`.e_${key}_input`).addClass('is-invalid');
                    // value berisi pesan error
                    // contohnya panggil .e_password_lama_error lalu textnya diisi dengan pesan errorya
                    $(`.e_${key}_error`).text(value);
                });
            // Jika user memasukkan password yang salah di input password lama
            // lain jika resp.pesan sama dengan "Password salah"
            } else if (resp.pesan === "Password lama salah") {
                // panggil #e_password_lama lalu tambah .is-invalid
                $(`#e_password_lama`).addClass('is-invalid');
                // panggil .e_password_error_lama_error lalu kasi text "Password lama salah"
                $(`.e_password_lama_error`).text('Password lama salah.');
            }
            // jika user memasukkan password lama di input password baru maka
            else if (resp.pesan === "Password baru tidak boleh sama dengan password lama") {
                // panggil #e_password_baru lalu tambah .is-invalid
                $(`#e_password_baru`).addClass('is-invalid');
                // panggil .e_password_error_baru_error lalu kasi text "Password baru tidak boleh sama dengan password lama."
                $(`.e_password_baru_error`).text(
                'Password baru tidak boleh sama dengan password lama.');
            }
            // Password berhasil diperbarui
            else if (resp.status === 200) {
                // kosongkan value #e_password_lama dan #e_password_baru
                // panggil #e_password_lama lalu value nya kasi string kosong agar terlihat kosong
                $("#e_password_lama").val("");
                // panggil #e_password_baru lalu value nya kasi string kosong agar terlihat kosong
                $("#e_password_baru").val("");
                // tutup modal
                // panggil #modal_edit_password lalu modal nya di sembunyikan
                $("#modal_edit_password").modal("hide");
                // notifikasi menggunakan sweetalert 2
                Swal.fire(
                    'Berhasil',
                    'Password Berhasil Diperbarui',
                    'success'
                );
            };
        });
    });

    // jika class e_tutup di click maka jalankan fungsi berikut untuk menghapus validasi error
    $(".e_tutup").on("click", function() {
        // panggil .e_input lalu hapus .is-invalid
        $(".e_input").removeClass('is-invalid');
        // panggil .e_pesan_error lalu text nya diisi "" agar menghapus pesan nya
        $(".e_pesan_error").text("");
    });
</script>
@endpush

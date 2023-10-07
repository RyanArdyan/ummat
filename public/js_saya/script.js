{{-- panggil script.js --}}
    <script type="module">
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then( /* ... */ )
            .catch( /* ... */ );



        // tampilkan pratinjau gambar ketika user mengubah gambar
        // jika #pilih_gambar_postingan diubah maka jalankan fungsi berikut
        $("#pilih_gambar_postingan").on("change", function() {
            // ambil gambarnya, this berarti #pilih_gambar_postingan, index ke 0
            let gambar = this.files[0];
            // jika ada gambar yang di pilih
            if (gambar) {
                // berisi baru FilePembaca
                let filePembaca = new FileReader();
                // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
                filePembaca.onload = function(e) {
                    // panggil #pratinjau_gambar_postingan lalu pangil attribute src diisi dengan acara.target.hasil
                    $("#pratinjau_gambar_postingan").attr("src", e.target.result);
                };
                // new FileReader() baca data sebagai url dari this.file[0]
                filePembaca.readAsDataURL(gambar);
            };
        });


        // Update
        // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event atau acara cegah bawaan nya yaitu reload atau muat ulang
            e.preventDefault();
            // berisi panggil #postingan_id lalu ambil value nya
            let postingan_id = $("#postingan_id").val();
            // jquery lakukan ajax
            $.ajax({
                    // ke method update
                    // panggil url /postingan/ lalu kirimkan value variable postingan_id
                    url: `/postingan/${postingan_id}`,
                    // panggil route tipe PUT karena sudah aku paksa ubah di formulir edit
                    type: "POST",
                    // kirimkan formulir data atau value input2x dari #form_edit
                    data: new FormData(this),
                    // aku butuh ketiga baris kode di bawah ini
                    processData: false,
                    contentType: false,
                    cache: false,
                    // hapus validasi error sebelum formulir di kirim
                    // sebelum kirim, jalankan fungsi berikut
                    beforeSend: function() {
                        // panggil .input lalu hapus class is-invalid
                        $(".input").removeClass("is-invalid");
                        // panggil .pesan_error lalu kosongkan text nya
                        $(".pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tangggapannya
                .done(function(resp) {
                    // cari saja karena gampang


                    // jika validasi error
                    // jika tangggapan.status sama dengan 0
                    if (resp.status === 0) {
                        // lain jika value berikut sama dengan string berikut maka panggil sweetalert
                        if (resp.errors.kategori_id[0] === 'Input kategori id harus diisi.') {
                            Swal.fire(
                                'Error!',
                                'Silahkan pilih dulu kategori nya!',
                                'error'
                            );
                        };

                        // tampilkan validasi error
                        // lakukan pengulangan kepada resp.error
                        // key berisi semua value attribute name yang error misalnya judul_postingan, value berisi pesan error nya misalnya "Nama postingan harus diisi"
                        // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                        $.each(resp.errors, function(key, value) {
                            // contohnya panggil .judul_postingan_input lalu tambah class is-invalid
                            $(`.${key}_input`).addClass('is-invalid');
                            // contohnya panggil .judul_postingan_error lalu isi textnya dengan paramter value
                            $(`.${key}_error`).text(value);
                        });
                    }
                    // jika validasi berhasil
                    else if (resp.status === 200) {
                        // berikan notifikasi menggunakan package sweetalert
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Berhasil menyimpan perubahan',
                            icon: 'success'
                        });
                        // setelah 2 detik 500 milidetik maka jalankan fungsi berikt
                        setTimeout(function() {
                            // panggil route postingan_.index
                            window.location.href = "{{ route('postingan.index') }}";
                        }, 2500);
                    };
                });
        });
    </script>
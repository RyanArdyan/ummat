<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Registrasi Ummat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Halaman Registrasi" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {{-- laravel mewajibkan keamanan dari serangan csrf, digunakan ketika aku melakukan ajax di script --}}
    {{-- cetak csrf_token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/gambar_pengaturan/logo_perusahaan.png') }}">

    <!-- App css -->
    <!-- bootstrap.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- icons.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- app.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    {{-- CSS Saya --}}
    <link rel="stylesheet" href="{{ asset('css_saya/style.css') }}">
</head>

<body class="authentication-bg">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center">

                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0">Registrasi Ummat</h4>
                            </div>

                            <form id="form_registrasi">
                                {{-- untuk keamanan --}}
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name">Nama Lengkap</label>
                                    {{-- untuk membuat efek error di input, aku butuh is-invalid --}}
                                    <input name="name" class="input name_input form-control" type="text" id="name"
                                        placeholder="Nama Lengkap" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="name_error pesan_error text-danger"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">Gmail</label>
                                    {{-- untuk membuat efek error di input, aku butuh is-invalid --}}
                                    <input name="email" class="input email_input form-control" type="text" id="email"
                                        placeholder="Gmail" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="email_error pesan_error text-danger"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    {{-- Untuk membuat efek validasi error di input, aku butuh is-invalid --}}
                                    <input name="password" class="input password_input form-control" type="password"
                                        id="password" placeholder="Password" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="password_error pesan_error text-danger"></p>
                                    <small id="lihat_password" class="text-primary jadikan_pointer">Lihat password</small>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <i class="mdi mdi-login"></i>
                                        Registrasi 
                                    </button>
                                </div>

                                {{-- panggil route yang bernama registrasi.buat --}}
                                <p class="mt-2"><a href="{{ route('login.index') }}" class="text-muted"><i
                                                class="iconify mr-1" data-icon="mdi-account-plus"></i>Sudah registrasi? click disini</a></p>
                            </form>


                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    {{-- asset akan memanggil public/adminto --}}
    <script src="{{ asset('adminto') }}/assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="{{ asset('adminto') }}/assets/js/app.min.js"></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- jquery --}}
    <script src="{{ asset('js_saya/jquery-3.6.3.min.js') }}"></script>
    {{-- icon dari web pictogrammers.com yang gratis dan lengkap --}}
    <script src="//code.iconify.design/1/1.0.6/iconify.min.js"></script>
    {{-- script saya --}}
    <script>
        // lihat password dan sembunyikan password
        $("#lihat_password").on("click", function() {
            // jika text pada #lihat_password sama dengan Lihat passsword maka
            if ($(this).text() === "Lihat password") {
                // #password, attribute type nya, ubah ke text
                $("#password").attr("type", "text");
                // #lihat_password, ubah textnya ke Sembunyikan password
                $(this).text("Sembunyikan password");
                // lain jika #lihat_password, textnya sama dengan sembunyikan password maka
            } else if ($(this).text() === "Sembunyikan password") {
                // #password, attribute type nya menjadi password
                $("#password").attr("type", "password");
                // #lihat_password, textnya menjadi lihat password
                $(this).text("Lihat password");
            };
        });

        // registrasi
        // jika #form_registrasi di kirim maka jalankan fungsi berikut lalu ambil event atau acaranya  nya
        $("#form_registrasi").on("submit", function(event) {
            // cegah bawaan yaitu reload
            // acara.cegahBawaan();
            event.preventDefault();
            //  lakukan ajax
            $.ajax({
                // panggil route registrasi.store
                url: `{{ route('registrasi.store') }}`,
                // panggil route type POST
                type: 'POST',
                // kirimknan data formulir atau input, data harus mengirimkan object
                // new FormData(this) secara otomatis membuat object
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah ketika aku menggunakan new FormData()
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim maka jalankan fungsi berikut lalu hapus validasi error
                beforeSend: () => {
                    // panggil .pesan_error, kosongkan textnya
                    $(".pesan_error").text("");
                    // panggil .input lalu hapus .is-invalid
                    $(".input").removeClass('is-invalid');
                },
                // laravel butuh csrf
                // tajuk-tajuk
                headers: {
                    // berisi panggil tag meta, name nya csrf-token, ambil value attribute content
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut
            // parameter response berisi data yang dikirimkan dari AutentikasiSessionController, method store, berarti berisi response.status, response.message, dll.
            .done((response) => {
                // jika validasi biasa error
                // jika value dari tanggapan.message yang dikirim controller berisi "Validasi Biasa Errors"
                if (response.message === 'Validasi Biasa Errors') {
                    // lakukan pengulangan
                    // key berisi semua value attribute name yang error
                    // value berisi pesan errornya
                    // $.setiap, tanggapan.kesalahan, jalankan fungsi, kunci, nilai
                    $.each(response.errors, function(key, value) {
                        // contoh nya panggil .name_input lalu tambahkan .adalah-tidak_salah
                        $(`.${key}_input`).addClass('is-invalid');
                        // contohnya panggil name_error lalu tambahkan pesan error nya
                        $(`.${key}_error`).text(value);
                    });
                }
                // lain jika login berhasil 
                // lain jika tanggapan.status berisi value 200
                else if (response.status === 200) {
                    // notifikasi menggunakan sweetalert
                    Swal.fire(
                        'Registrasi Berhasil',
                        'Halaman akan dimuat ulang.',
                        'success'
                    );
                    // setelah 2 detik maka jalankan fungsi berikut
                    setTimeout(() => {
                        // lokasi.href panggil url awal
                        location.href = "/";
                    }, 2000);
                };
            });
        });
    </script>
</body>
</html>

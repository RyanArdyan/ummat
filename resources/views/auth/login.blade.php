<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Login Ummat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Halaman Login" name="description" />
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
                                <h4 class="text-uppercase mt-0">Login Ummat</h4>
                            </div>

                            <form id="form_login">
                                {{-- untuk keamanan --}}
                                @csrf
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



                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin"
                                            checked>
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <i class="mdi mdi-login"></i>
                                        Log In 
                                    </button>
                                </div>

                                <p class="mt-2"><a href="#" class="text-muted"><i
                                            class="fa fa-lock mr-1"></i>Lupa password? click disini</a></p>
                                {{-- panggil route yang bernama registrasi.buat --}}
                                <p class="mt-2"><a href="{{ route('registrasi.create') }}" class="text-muted"><i
                                                class="iconify mr-1" data-icon="mdi-account-plus"></i>Belum registrasi? click disini</a></p>
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
    {{-- icons --}}
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

        // login
        // jika #form_login di kirim maka jalankan fungsi berikut lalu ambil event atau acaranya  nya
        $("#form_login").on("submit", function(event) {
            // cegah bawaan yaitu reload
            // acara.cegahBawaan();
            event.preventDefault();
            // jquery lakukan ajax
            $.ajax({
                // panggil route login.store
                url: `{{ route('login.store') }}`,
                // panggil route type POST
                type: 'POST',
                // kirimkan semua nilai input atau formulir
                // data harus mengirimkan object
                // new FormData(this) secara otomatis membuat object
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah
                // proses_data: salah
                processData: false,
                // konten tipe: salah
                contentType: false,
                // cache: salah
                cache: false,
                // sebelum kirim jalankan fungsi berikut untuk menghapus validasi error
                beforeSend: () => {
                    // panggil .input lalu hapus .is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error, kosongkan textnya
                    $(".pesan_error").text("");
                },
                // laravel butuh csrf
                // tajuk2x berisi object
                headers: {
                    // panggil tag meta, name nya csrf-token, ambil value attribute content
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut
            .done((response) => {
                // jika validasi biasa error
                // jika value tanggapan.pesan sama dengan "Validasi Biasa Errors"
                if (response.message === 'Validasi Biasa Errors') {
                    // lakukan pengulangan
                    // key berisi semua value attribute name yang error berarti email dan password
                    // value berisi pesan errornya misalnya "Email wajib diisi"
                    // jquery.setiap(tanggapan.kesalahan2x, fungsi(kunci, nilai)) 
                    $.each(response.errors, function(key, value) {
                        // contoh nya panggil .email_input lalu tambahkan .is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // panggil class value parameter key lalu digabung _error contohnya panggil email_error lalu tambahkan value parameter value yaitu pesan error nya
                        $(`.${key}_error`).text(value);
                    });   
                } 
                // lain jika email yang di input tidak ada di table users
                // lain jika value tanggapan.pesan sama dengan "Email belum terdaftar"
                else if (response.message === 'Email belum terdaftar') {
                    // panggil #email lalu tambahkan class is-invalid
                    $('#email').addClass('is-invalid');
                    // panggil .email_error lalu tambahkan text "Email belum terdaftar, silahkan registrasi"
                    $('.email_error').text('Email belum terdaftar, silahkan registrasi.');
                } 
                // fitur password salah
                // lain jika value tanggapan.pesan sama dengan 'Password salah.'
                else if (response.message === 'Password salah.') {
                    // panggil #password lalu tambahkan class is-invalid agar border input nya berwarna merah
                    $('#password').addClass('is-invalid');
                    // panggil .password_error lalu tambahkan text 'Password salah.'
                    $('.password_error').text('password salah.');
                } 
                // lain Jika user berhasil login
                else {
                    // notifikasi menggunakan package web sweetalert2
                    Swal.fire(
                        'Login Berhasil',
                        'Tolong tunggu, Halaman akan dimuat ulang.',
                        'success'
                    );
                    // berisi value tanggapan.is_admin yang dikirim dari controller
                    let is_admin = response.is_admin;
                    
                    // setelah 2 detik maka jalankan fungsi berikut
                    setTimeout(() => {
                        // jika yang login adalah jamaah
                        // jika value variable is_admin sama dengan "0" maka dia adalah jamaah maka arahkan ke url / atau halaman home.
                        if (is_admin === "0") {
                            // arahkan ke url awal
                            // lokasi.href ke url awal
                            location.href = '/';
                        }
                        // lain jika yang login adalah admin
                        else if (is_admin === "1") {
                            // panggil route dashboard
                            // lokasi.href berisi panggil rute dashboard
                            location.href = `{{ route('dashboard.index') }}`;
                        };
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html>

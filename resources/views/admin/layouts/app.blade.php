<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        {{-- menangkap value dari @section('title') --}}
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        {{-- csrf token, laravel mewajibkan keamanan dari serangan csrf --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        {{-- logo web di sebelah title --}}
        {{-- asset berarti memanggil folder public --}}
        <link rel="shortcut icon" href="{{ asset('storage/logo_web/logo.jpg') }}">

        <!-- Bootstrap -->
        <link href="{{ asset('adminto') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        {{-- icons --}}
        <link href="{{ asset('adminto') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        {{-- app --}}
        <link href="{{ asset('adminto') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        {{-- paket datatables css --}}
        <link href="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.css" rel="stylesheet"/>

        {{-- toastr css --}}
        <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

        {{-- berfungsi menangkap value @push('css') --}}
        @stack('css')
        
        
    </head>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            {{-- @termasuk view berikut --}}
            @include('admin.layouts.top-navbar')
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('admin.layouts.left_side')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        {{-- Ini adalah tampilan child nya --}}
                        {{-- @menghasilkan konten --}}
                        @yield('konten')
                    </div> <!-- container-fluid -->

                </div> <!-- content -->

                <!-- Footer Start -->

                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        {{-- JQuery --}}
        {{-- asset akan memanggil folder public --}}
        <script src="{{ asset('js_saya/jquery-3.7.0.min.js') }}"></script>
        <!-- Vendor js -->
        <script src="{{ asset('adminto') }}/assets/js/vendor.min.js"></script>
        <!-- knob plugin --> 
        <script src="{{ asset('adminto') }}/assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <!-- App js -->
        <script src="{{ asset('adminto') }}/assets/js/app.min.js"></script>
        {{-- datatables js --}}
        <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>
        {{-- sweetalert 2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- toastr js --}}
        <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

        {{-- Script child akan di push kesini(parent) menggunakan @push('script') --}}
        @stack('script')
        {{-- fitur tooltip atau misalnya aku hover tombol hapus maka muncul sebuah text box yang menyatakan hapus --}}
        <script>
            // jika document siap maka jalankan fungsi
            $(document).ready(function () {
                // panggil attribute data-toggle yang berisi keterangan_alat
                $('[data-toggle="keterangan_alat"]').tooltip()
            });
        </script>
    </body>
</html>
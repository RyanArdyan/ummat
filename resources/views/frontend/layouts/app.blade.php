<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- csrf token, laravel mewajibkan keamanan dari serangan csrf --}}
    {{-- cetak, panggil fungsi csrf_token bawaan milik laavel --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- tangkap value @sectin('title') milik child nya --}}
    <title>@yield('title')</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    {{-- datatables css --}}
    <link href="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.css" rel="stylesheet"/>
    
    {{-- tangkap value @push('css') dari child nya ke dalam @tumpukan('css') --}}
    @stack('css')
</head>

<body>


    {{-- Navbar Atas --}}
    {{-- @termasuk view berikut --}}
    @include('frontend.layouts.top_navbar')

    {{-- @menghasilkan('konten') --}}
    @yield('konten')

    <footer class="bg-light text-center text-lg-start mt-5">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
          © 2023 Copyright:
          <a class="text-dark" href="#">Ummat.com</a>
        </div>
        <!-- Copyright -->
    </footer>

    <!-- BOOTSTRAP BUNDLE CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    {{-- JQuery --}}
    {{-- asset akan memanggil folder public --}}
    <script src="{{ asset('js_saya/jquery-3.7.0.min.js') }}"></script>
    {{-- datatables js --}}
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>
    {{-- Script child akan di push kesini(parent) menggunakan @push('script') --}}
    @stack('script')
</body>
</html>

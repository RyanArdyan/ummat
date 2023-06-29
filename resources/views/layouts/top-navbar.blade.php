@php
    // // panggil model pengaturan
    // use App\Models\Pengaturan;
    // // berisi ambil detail pengaturan pertama
    // $detail_pengaturan = Pengaturan::first();
@endphp


<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                {{-- asset() akan memanggil folder public --}}
                {{-- jika ada value detail_user yang login, column foto maka panggil value detail_user yg login column foto, jika tidak ada maka panggil default_foto.jpg --}}
                <img src="{{ (auth()->user()->foto) ? asset('storage/foto_profil/' . auth()->user()->foto . '') : asset('storage/foto_profil/default_foto.jpg') }}" class="rounded-circle foto_profil">
                <span class="pro-user-name ml-1 nama_user">
                    {{-- cetak value detail user yang login, column name --}}
                    {{-- cetak autentikasi()->pengguna()->nama --}}
                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i> 
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                {{-- jika jamaah yang login --}}
                {{-- jika yang login, value column is_jamaah nya adalah "0" maka --}}
                {{-- @jika (autentikasi()->is_jamaah === "0") --}}
                @if (auth()->user()->is_admin === "0")
                    <!-- Edit Profile-->
                    {{-- jika menu Edit Profile di click maka panggil route edit_profile --}}
                    {{-- class adropddown-item adalah .active --}}
                    {{-- jika permintaan adalah edit-profile* maka aktifkan kan menu nya kalau bukan maka jangan aktifkan --}}
                    {{-- *  berarti apapun setelah nya --}}
                    <a href="{{ route('edit_profile') }}" class="{{ Request()->is('edit-profile*') ? 'adropdown-item' : 'dropdown-item' }} notify-item">
                        <i class="mdi mdi-account-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                @endif

                {{-- jika admin yang login --}}
                {{-- jika yang login, value column is_admin nya adalah "1" maka --}}
                {{-- @jika (autentikasi()->is_admin === "1") --}}
                @if (auth()->user()->is_admin === "1")

                    <!-- Edit Profile-->
                    {{-- jika menu Edit Profile di click maka panggil route edit_profile --}}
                    {{-- class adropddown-item adalah .active --}}
                    {{-- jika permintaan adalah edit-profile* maka aktifkan kan menu nya kalau bukan maka jangan aktifkan --}}
                    {{-- *  berarti apapun setelah nya --}}
                    <a href="{{ route('edit_profile') }}" class="{{ Request()->is('edit-profile*') ? 'adropdown-item' : 'dropdown-item' }} notify-item">
                        <i class="mdi mdi-account-edit"></i>
                        <span>Edit Profile</span>
                    </a>

                    <!-- Pengaturan-->
                    {{-- ke route pengaturan.index --}}
                    {{-- jika permintaan adalah url pengaturan dan apapun setelah itu maka aktifkan menu nya, kalau bukan maka ksongkan --}}
                    <a href="#" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>Pengaturan</span>
                    </a>
                @endif

                <div class="dropdown-divider"></div>

                {{-- form ketika dikirim panggil route tipe kirim, pangil route yang bernama logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    {{-- laravel mewajibkan csrf untuk keamanan dari serangan csrf --}}
                    @csrf
                    <!-- item-->
                    <button type="submit" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </button>
                </form>

            </div>
        </li>


    </ul>

    <!-- LOGO paling sebelah kiri -->
    <div class="logo-box">
        <a href="javascript:void(0)" class="logo text-center mt-3">
            <span class="logo-lg">

                {{-- asset() akan memanggil folder public --}}
                {{-- jika ada value detail_user yang login, column foto maka panggil value detail_user yg login column foto, jika tidak ada maka panggil default_foto.jpg --}}
                <img src="{{ (auth()->user()->foto) ? asset('storage/foto_profil/' . auth()->user()->foto . '') : asset('storage/foto_profil/default_foto.jpg') }}" alt="Foto Profile" height="50" class="foto_profil rounded-circle">
                <!-- <span class="logo-lg-text-light">Xeria</span> -->
            </span>
            <span class="logo-sm">
                {{-- asset() akan memanggil folder public --}}
                {{-- jika ada value detail_user yang login, column foto maka panggil value detail_user yg login column foto, jika tidak ada maka panggil default_foto.jpg --}}
                <img src="{{ (auth()->user()->foto) ? asset('storage/foto_profil/' . auth()->user()->foto . '') : asset('storage/foto_profil/default_foto.jpg') }}" alt="Foto Profile" height="50" class="foto_profil rounded-circle">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile disable-btn waves-effect">
                <i class="fe-menu"></i>
            </button>
        </li>

        <li>
            {{-- tangkap section('title') --}}
            <h4 class="page-title-main">@yield('title')</h4>
        </li>

    </ul>
</div>
<!-- end Topbar -->
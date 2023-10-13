<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!-- User box -->
        <div class="user-box text-center">
            <div class="dropdown">
                <a href="#" class="nama_user text-dark dropdown-toggle h5 mt-2 mb-1 d-block" {{-- cetak value column name dari detail user yang login --}}
                    {{-- autentikasi()->pengguna->nama --}} data-toggle="dropdown">{{ auth()->user()->name }}</a>
            </div>
            {{-- cetak value column email dari detail user yang login --}}
            <p class="text-muted">{{ auth()->user()->email }}</p>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="text-muted">
                        <i class="mdi mdi-settings"></i>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a href="#" class="text-custom">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
            </ul>
        </div>


        {{-- ubah donasi. menjadi admin.donasi. --}}


        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Navigasi</li>
                    {{-- home --}}
                    <li>
                        {{-- panggil route admin.home.index --}}
                        {{-- jika permintaan adalah route home. dan apapun setalah nya maka tambahkan class active, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('admin.home.index') }}"
                            class="{{ Request()->routeIs('admin.home.*') ? 'active' : '' }}">
                            <i class="mdi mdi-home-account"></i>
                            <span> Home </span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);">
                            <i class="mdi mdi-calendar-account"></i>
                            <span> Master Data </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            {{-- Cetak jika permintaan adalah route admin.kegiatan_rutin. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.kegiatan_rutin.*') ? 'active' : '') }}">
                                {{-- panggil route admin.kegiatan_rutin.index --}}
                                <a href="{{ route('admin.kegiatan_rutin.index') }}">Kegiatan Rutin</a>
                            </li>

                            {{-- cetak jika permintaan adalah route admin.kegiatan_sekali. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.kegiatan_sekali.*') ? 'active' : '') }}">
                                {{-- panggil route admin.kegiatan_sekali.index --}}
                                <a href="{{ route('admin.kegiatan_sekali.index') }}">Kegiatan sekali</a>
                            </li>

                            {{-- ubah semua url /doa tambahakan /admin/ diawal dan doa. dan awal nya di admin, pindahkan dulu url /doa ke middlware /admin karena hanya admin yang boleh mengakses /doa --}}


                            {{-- cetak jika permintaan adalah route admin.doa. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.doa.*') ? 'active' : '') }}">
                                {{-- panggil route admin.doa.index --}}
                                <a href="{{ route('admin.doa.index') }}">Doa Pendek</a>
                            </li>

                            {{-- cetak jika permintaan adalah route admin.kategori. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.kategori.*') ? 'active' : '') }}">
                                {{-- panggil route admin.kategori.index --}}
                                <a href="{{ route('admin.kategori.index') }}">Kategori</a>
                            </li>
                            {{-- cetak jika permintaan adalah route admin.postingan. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.postingan.*') ? 'active' : '') }}">
                                {{-- panggil route admin.postingan.index --}}
                                <a href="{{ route('admin.postingan.index') }}">Postingan</a>
                            </li>



                            {{-- cetak jika permintaan adalah route admin.penceramah. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.penceramah.*') ? 'active' : '') }}">
                                {{-- panggil route penceramah.index --}}
                                <a href="{{ route('admin.penceramah.index') }}">Penceramah</a>
                            </li>
                            {{-- cetak jika permintaan adalah route admin.donasi. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.donasi.*') ? 'active' : '') }}">
                                {{-- panggil route admin.donasi.index --}}
                                <a href="{{ route('admin.donasi.index') }}"  style="pointer-events: none; color: #999; text-decoration: none;"    >Donasi Menggunakan Payment Gateway</a>
                            </li>
                            {{-- cetak jika permintaan adalah route berikut dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('admin.cek_kebenaran_donasi_manual.*') ? 'active' : '') }}">
                                {{-- panggil route berikut --}}
                                <a href="{{ route('admin.cek_kebenaran_donasi_manual.index') }}">Cek Kebenaran Donasi</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Tampilan Jmaah --}}
                    <li>
                        {{-- panggil route frontend.index --}}
                        <a href="{{ route('frontend.index') }}">
                            <i class="mdi mdi-home-account"></i>
                            <span> Tampilan Jamaah </span>
                        </a>
                    </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

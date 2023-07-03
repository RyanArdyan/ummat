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

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Navigasi</li>
                    {{-- home --}}
                    <li>
                        {{-- panggil route home.index --}}
                        {{-- jika permintaan adalah route home. dan apapun setalah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('home.index') }}"
                            class="{{ Request()->routeIs('home.*') ? 'active' : '' }}">
                            <i class="mdi mdi-home-account"></i>
                            <span> home </span>
                        </a>
                    </li>

                    {{-- Kegiatan --}}
                    <li>
                        <a href="javascript: void(0);">
                            <i class="mdi mdi-calendar-account"></i>
                            <span> Master Data </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            {{-- Cetak jika permintaan adalah route kegiatan_rutin. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('kegiatan_rutin.*') ? 'active' : '') }}">
                                {{-- panggil route kegiatan_rutin.index --}}
                                <a href="{{ route('kegiatan_rutin.index') }}">Kegiatan Rutin</a>
                            </li>

                            {{-- cetak jika permintaan adalah route kegiatan_sekali. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('kegiatan_sekali.*') ? 'active' : '') }}">
                                {{-- panggil route kegiatan_sekali.index --}}
                                <a href="{{ route('kegiatan_sekali.index') }}">Kegiatan sekali</a>
                            </li>

                            {{-- cetak jika permintaan adalah route doa. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('doa.*') ? 'active' : '') }}">
                                {{-- panggil route doa.index --}}
                                <a href="{{ route('doa.index') }}">Doa Pendek</a>
                            </li>
                            {{-- cetak jika permintaan adalah route kategori. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('kategori.*') ? 'active' : '') }}">
                                {{-- panggil route kategori.index --}}
                                <a href="{{ route('kategori.index') }}">Kategori</a>
                            </li>
                            {{-- cetak jika permintaan adalah route postingan. dan * berarti apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->routeIs('postingan.*') ? 'active' : '') }}">
                                {{-- panggil route postingan.index --}}
                                <a href="{{ route('postingan.index') }}">Postingan</a>
                            </li>
                        </ul>
                    </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

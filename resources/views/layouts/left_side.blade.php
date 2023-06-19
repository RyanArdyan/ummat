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
                {{-- jika kasir yang login --}}
                {{-- jika user yang login, value column is_admin nya adalah "0" maka --}}
                @if (auth()->user()->is_admin === '0')
                    {{-- Dashboard --}}
                    <li>
                        {{-- jika permintaan adalah dashboard maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('dashboard.index') }}"
                            class="{{ Request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                @endif

                {{-- jika admin yang login --}}
                {{-- jika yang login, value column is_admin nya adalah 1 maka --}}
                {{-- @jika (autentikasi()->pengguna()->adalah_admin === 1) --}}
                @if (auth()->user()->is_admin === '1')
                    {{-- Dashboard --}}
                    <li>
                        {{-- panggil route dashboard.index --}}
                        {{-- jika permintaan adalah url dashboard dan apapun setalah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('dashboard.index') }}"
                            class="{{ Request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    {{-- Kegiatan --}}
                    <li>
                        <a href="javascript: void(0);">
                            <i class="mdi mdi-calendar-account"></i>
                            <span> Kegiatan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            {{-- panggil route kegiatan_rutin.index --}}
                            {{-- Jika permintaan adalah url kegiatan-rutin dan apapun setelah nya maka aktifkan, kalau bukan maka kasi string kosong --}}
                            <li class="{{ (request()->is('kegiatan-rutin*') ? 'active' : '') }}"><a href="{{ route('kegiatan_rutin.index') }}">Kegiatan Rutin</a></li>
                            <li class="active"><a href="ui-cards.html">Kegiatan Sekali</a></li>
                        </ul>
                    </li>
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

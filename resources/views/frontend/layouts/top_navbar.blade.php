<!-- Navbar atau menu navigasi atas -->
<nav class="navbar navbar-expand-lg bg-danger p-4 position-relative" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                {{-- jika permintaan adalah route frontend.index maka tambahkan class active, kalau bukan maka kasi string kosong, panggil route frontend.index --}}
                <a class="{{ Request()->routeIs('frontend.index') ? 'active' : '' }} nav-link" href="{{ route('frontend.index') }}">Beranda</a>
                {{-- jika permintaan adalah route frontend.kegiatan.index maka tambahkan class active, kalau bukan maka kasi string kosong, panggil route frontend.kegiatan.index --}}
                <a class="{{ Request()->routeIs('frontend.kegiatan.index') ? 'active' : '' }} nav-link" href="{{ route('frontend.kegiatan.index') }}">Kegiatan</a>
                {{-- jika permintaan adalah route frontend.doa.index maka tambahkan class active, kalau bukan maka kasi string kosong, --}}
                {{-- cetak, panggil route frontend.doa.index --}}
                <a class="{{ Request()->routeIs('frontend.doa.index') ? 'active' : '' }} nav-link" href="{{ route('frontend.doa.index') }}">Daftar Doa</a>
                {{-- jika permintaan adalah route frontend.artikel. dan apapun setelah itu maka tambahkan class active, kalau bukan maka kasi string kosong, --}}
                {{-- cetak, panggil route frontend.artikel.index --}}
                <a class="{{ Request()->routeIs('frontend.artikel.*') ? 'active' : '' }} nav-link" href="{{ route('frontend.artikel.index') }}">Artikel</a>
                {{-- jika permintaan adalah route frontend.penceramah. dan apapun setelah itu maka tambahkan class active, kalau bukan maka kasi string kosong, --}}
                {{-- cetak, panggil route frontend.penceramah.index --}}
                <a class="{{ Request()->routeIs('frontend.penceramah.*') ? 'active' : '' }} nav-link" href="{{ route('frontend.penceramah.index') }}">Penceramah</a>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Donasi
                    </a>
                    <ul class="dropdown-menu">
                        {{-- cetak panggil route berikut --}}
                        <li><a  style="pointer-events: none; color: #999; text-decoration: none;" class="dropdown-item" href="{{ route('admin.donasi.create') }}">Menggunakan Payment Gateway</a></li>
                        {{-- cetak panggil route donasi_manual.create  --}}
                        <li><a class="dropdown-item" href="{{ route('donasi_manual.create') }}">Secara Manual</a></li>
                    </ul>
                </li>

                {{-- jika user sudah login --}}
                @auth
                    {{-- jika sudah login dan dia adalah admin atau jika autentikasi, value detail_user yg login, column is_admin nya sama dengan "1" maka tampilkan menu Dashboard Admin --}}
                    @if (auth()->user()->is_admin === '1')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{-- cetak autentikasi, value detail_user yg login, column name --}}
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                {{-- cetak panggil route berikut --}}
                                <li><a class="dropdown-item" href="{{ route('admin.home.index') }}">Dashboard Admin</a></li>
                                {{-- cetak panggil route edit_profile  --}}
                                <li><a class="dropdown-item" href="{{ route('edit_profile') }}">Edit Profile</a></li>

                                {{-- form ketika dikirim panggil route tipe kirim, pangil route yang bernama logout --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    {{-- laravel mewajibkan csrf untuk keamanan dari serangan csrf --}}
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </ul>
                        </li>
                    {{-- lain jika yg login adalah jamaah --}}
                    {{-- lain jika value autentikasi, detail_user yang login, column is_admin sama dengan 0 --}}
                    @elseif (auth()->user()->is_admin === '0')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{-- cetak autentikasi, value detail_user yg login, column name --}}
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                {{-- cetak panggil route edit_profile  --}}
                                <li><a class="dropdown-item" href="{{ route('edit_profile') }}">Edit Profile</a></li>
                                {{-- form ketika dikirim panggil route tipe kirim, pangil route yang bernama logout --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    {{-- laravel mewajibkan csrf untuk keamanan dari serangan csrf --}}
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </ul>
                        </li>
                    @endif

                    {{-- aku copas tampilan dari detail donasi karena nanti jika ada user yang belum melakukan pembayaran maka tampil card pembayaran yg belum di bayar --}}


                    {{-- cetak, jika permintaan adalah route berikut maka tambahkan class active, kalau bukan maka kasi string kosong, --}}
                    {{-- cetak, panggil route donasi.menunggu_pembayaran --}}
                    <a  style="pointer-events: none; color: #999; text-decoration: none;" class="{{ Request()->routeIs('donasi.menunggu_pembayaran') ? 'active' : '' }} nav-link" href="{{ route('donasi.menunggu_pembayaran') }}">menunggu_pembayaran</a>
                @endauth


                
                {{-- lain jika dia adalah pengunjung yang belum login --}}
                @guest 
                    <a class="nav-link" href="/login">Login</a>
                @endguest

                <a class="navbar-brand position-absolute end-0 me-4" href="#">Ummat</a>

            </div>
        </div>
    </div>
</nav>

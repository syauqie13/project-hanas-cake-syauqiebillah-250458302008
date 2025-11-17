<div>
    <nav>
        <ul>
            <li class="logo">✨ HANA'S CAKE</li>
            <div class="menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#products">Produk</a></li>
                <li><a href="#testimonials">Testimoni</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">Kontak</a></li>
            </div>
            <div class="auth-menu">
                @if (Route::has('login'))
                    @auth

                        {{-- ROLE ADMIN --}}
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">
                                Dashboard
                            </a>

                            {{-- ROLE KARYAWAN --}}
                        @elseif (Auth::user()->role === 'karyawan')
                            <a href="{{ route('karyawan.dashboard') }}" class="btn-dashboard">
                                Dashboard
                            </a>

                            {{-- ROLE PELANGGAN --}}
                        @elseif (Auth::user()->role === 'pelanggan')
                            <a href="{{ route('ecommerce') }}" class="btn-dashboard">
                                Ecommerce
                            </a>
                        @endif

                    @else
                        {{-- GUEST: LOGIN --}}
                        <a href="{{ route('login') }}" class="btn-login">
                            Log in
                        </a>

                        {{-- GUEST: REGISTER --}}
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-register">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </ul>
    </nav>
</div>

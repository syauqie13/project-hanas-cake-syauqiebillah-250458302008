<div>
    <nav class="fixed z-50 w-full glass-effect">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center">
                    <i class="mr-2 text-2xl text-purple-600 fas fa-bolt"></i>
                    <span class="text-xl font-bold text-white">QieFlow.</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden space-x-8 md:flex">
                    <a href="#features" class="text-white transition hover:text-purple-300">Fitur</a>
                    <a href="#benefits" class="text-white transition hover:text-purple-300">Keuntungan</a>
                    <a href="#pricing" class="text-white transition hover:text-purple-300">Harga</a>
                    <a href="#contact" class="text-white transition hover:text-purple-300">Kontak</a>
                </div>

                {{-- Auth / Actions --}}
                <div>
                    @if (Route::has('login'))
                        <nav class="flex items-center justify-end gap-4">

                            @auth
                                {{-- ROLE: ADMIN --}}
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="px-6 py-2 font-semibold text-white transition rounded-full shadow-lg bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                                        <i class="mr-2 fas fa-tachometer-alt"></i>
                                        Dashboard
                                    </a>

                                    {{-- ROLE: KARYAWAN --}}
                                @elseif (Auth::user()->role === 'karyawan')
                                    <a href="{{ route('karyawan.dashboard') }}"
                                        class="px-6 py-2 font-semibold text-white transition rounded-full shadow-lg bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                                        <i class="mr-2 fas fa-tachometer-alt"></i>
                                        Dashboard
                                    </a>

                                    {{-- ROLE: PELANGGAN --}}
                                @elseif (Auth::user()->role === 'pelanggan')
                                    <a href="{{ route('ecommerce') }}"
                                        class="px-6 py-2 font-semibold text-white transition rounded-full shadow-lg bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                                        <i class="mr-2 fas fa-tachometer-alt"></i>
                                        Ecommerce
                                    </a>
                                @endif

                            @else
                                {{-- GUEST: LOGIN --}}
                                <a href="{{ route('login') }}"
                                    class="px-6 py-2 font-semibold text-white transition hover:text-purple-200">
                                    Log in
                                </a>

                                {{-- GUEST: REGISTER --}}
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="px-6 py-2 font-semibold text-purple-600 transition bg-white rounded-full shadow-lg hover:bg-purple-50">
                                        Register
                                    </a>
                                @endif

                            @endauth
                        </nav>
                    @endif
                </div>

            </div>
        </div>
    </nav>
</div>

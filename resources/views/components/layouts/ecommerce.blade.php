<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Hana Cake E-Commerce' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('icon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('icon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('icon/site.webmanifest') }}" />
    @livewireStyles

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    </style>

    <link rel="stylesheet" href="{{ asset('css-ecommerce.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50">

    <nav class="sticky top-0 z-50 shadow-lg glass" x-data="{ mobileMenuOpen: false }">
        <div class="container px-6 py-4 mx-auto">
            <div class="flex items-center justify-between">

                <a href="{{ route('ecommerce') }}" class="flex items-center space-x-3 group">
                    <div
                        class="flex items-center justify-center w-10 h-10 transition-transform duration-300 transform bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl group-hover:rotate-12">
                        <i class="text-xl text-white fas fa-birthday-cake"></i>
                    </div>
                    <span
                        class="text-2xl font-bold text-transparent bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                        Hana Cake
                    </span>
                </a>

                <div class="flex items-center space-x-4 md:hidden">
                    <div class="md:hidden">
                        <livewire:frontend.cart-counter />
                    </div>

                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-600 hover:text-purple-600 focus:outline-none">
                        <i class="text-2xl fas fa-bars" x-show="!mobileMenuOpen"></i>
                        <i class="text-2xl fas fa-times" x-show="mobileMenuOpen" style="display: none;"></i>
                    </button>
                </div>

                <div class="items-center hidden space-x-8 md:flex">
                    <a href="{{ route('ecommerce') }}" wire:navigate
                        class="relative font-medium text-gray-700 transition-colors hover:text-purple-600 group">
                        <span>Shop (PO)</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-600 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>

                    <livewire:frontend.cart-counter />

                    @auth
                        @if(Auth::user()->role == 'pelanggan')

                            <div class="relative group" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="flex items-center space-x-2 font-medium text-gray-700 transition-colors hover:text-purple-600 focus:outline-none">

                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                                        alt="Avatar" class="object-cover w-8 h-8 border-2 border-purple-100 rounded-full">

                                    <span>{{ Auth::user()->name }}</span>

                                    <i class="text-xs transition-transform duration-200 fas fa-chevron-down"
                                        :class="{'rotate-180': open}"></i>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    style="display: none;"
                                    class="absolute right-0 z-50 w-56 py-2 mt-2 origin-top-right bg-white border border-gray-100 shadow-xl rounded-xl">

                                    <div class="px-4 py-2 mb-1 border-b border-gray-50">
                                        <p class="text-xs font-bold tracking-wider text-gray-500 uppercase">Akun Saya</p>
                                    </div>

                                    <a href="{{ route('pelanggan.profile') }}" wire:navigate
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                        <i class="w-5 mr-2 text-center text-gray-400 fas fa-user-edit"></i> Edit Profil
                                    </a>

                                    <a href="{{ route('pelanggan.my-orders') }}" wire:navigate
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                        <i class="w-5 mr-2 text-center text-gray-400 fas fa-shopping-bag"></i> Pesanan Saya
                                    </a>

                                    <div class="my-1 border-t border-gray-50"></div>

                                    <button onclick="confirmLogout()"
                                        class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="w-5 mr-2 text-center fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </div>
                            </div>

                        @else
                            <a href="{{ route('karyawan.pos') }}" wire:navigate
                                class="flex items-center px-5 py-2 text-sm font-medium text-white transition-all duration-300 rounded-full bg-gradient-to-r from-orange-500 to-red-500 hover:shadow-lg hover:scale-105">
                                <i class="mr-2 fas fa-cash-register"></i>Masuk POS
                            </a>
                        @endif
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" wire:navigate
                                class="font-medium text-gray-600 transition-colors hover:text-purple-600">
                                Login
                            </a>
                            <a href="{{ route('register') }}" wire:navigate
                                class="flex items-center px-5 py-2 font-medium text-white rounded-full shadow-md btn-gradient">
                                <i class="mr-2 text-sm fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="pb-4 mt-4 border-t border-gray-100 md:hidden" style="display: none;">

                <div class="flex flex-col mt-4 space-y-3">
                    <a href="{{ route('ecommerce') }}" wire:navigate
                        class="block px-4 py-2 font-medium text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-600">
                        <i class="w-6 mr-2 text-center fas fa-store"></i> Shop (PO)
                    </a>

                    @auth
                        @if(Auth::user()->role == 'pelanggan')
                            <div class="px-4 pt-2 my-2 border-t border-gray-100">
                                <div class="flex items-center mb-3">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                                        alt="Avatar" class="object-cover w-8 h-8 mr-3 border-2 border-purple-100 rounded-full">
                                    <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                                </div>
                            </div>

                            <a href="{{ route('pelanggan.profile') }}" wire:navigate
                                class="block px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-600">
                                <i class="w-6 mr-2 text-center fas fa-user-edit"></i> Edit Profil
                            </a>

                            <a href="{{ route('pelanggan.my-orders') }}" wire:navigate
                                class="block px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-600">
                                <i class="w-6 mr-2 text-center fas fa-shopping-bag"></i> Pesanan Saya
                            </a>

                            <button onclick="confirmLogout()"
                                class="w-full px-4 py-2 mt-2 text-sm text-left text-red-600 rounded-lg hover:bg-red-50">
                                <i class="w-6 mr-2 text-center fas fa-sign-out-alt"></i> Logout
                            </button>
                        @else
                            <a href="{{ route('karyawan.pos') }}" wire:navigate
                                class="block px-4 py-2 mx-4 mt-2 text-sm font-medium text-center text-white rounded-full shadow-md bg-gradient-to-r from-orange-500 to-red-500">
                                <i class="mr-2 fas fa-cash-register"></i>Masuk POS
                            </a>
                        @endif
                    @else
                        <div class="flex flex-col px-4 pt-4 my-2 space-y-3 border-t border-gray-100">
                            <a href="{{ route('login') }}" wire:navigate
                                class="block w-full py-2 text-center text-gray-600 border border-gray-300 rounded-full hover:bg-gray-50">
                                Login
                            </a>
                            <a href="{{ route('register') }}" wire:navigate
                                class="block w-full py-2 text-center text-white rounded-full shadow-md btn-gradient">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </nav>

    <form method="POST" action="{{ route('pelanggan.logout') }}" id="logout-form" class="hidden">
        @csrf
    </form>

    {{ $slot }}

    <footer class="py-8 mt-16 text-white gradient-bg">
        <div class="container px-6 mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-white rounded-xl">
                    <i class="text-xl text-purple-600 fas fa-birthday-cake"></i>
                </div>
                <span class="text-2xl font-bold">Hana Cake</span>
            </div>
            <p class="text-purple-100">© 2025 Hana Cake. Semua hak dilindungi.</p>
        </div>
    </footer>

    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan dikembalikan ke halaman utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal',
                background: '#fff',
                backdrop: 'rgba(139, 92, 246, 0.2)'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                if (typeof Swal !== 'undefined') {
                    const isError = data.icon === 'error';

                    Swal.fire({
                        icon: data.icon || 'success',
                        title: isError ? 'Oops...' : 'Berhasil!',
                        text: data.message,
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,

                        // Styling lebih modern
                        background: isError ? '#fde8e8' : '#ecfdf5',
                        color: isError ? '#b91c1c' : '#047857',

                        // Icon lebih besar
                        customClass: {
                            icon: 'swal-custom-icon',
                            popup: 'swal-custom-popup'
                        },

                        // Animasi lebih smooth
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown animate__faster'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp animate__faster'
                        }
                    });
                } else {
                    alert(data.message);
                }
            });


            Livewire.on('showLoginWarning', () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Anda Belum Login',
                        text: "Silakan login terlebih dahulu untuk melanjutkan checkout.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8b5cf6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="mr-2 fas fa-sign-in-alt"></i>Login Sekarang',
                        cancelButtonText: 'Batal',
                        background: '#fff',
                        backdrop: 'rgba(139, 92, 246, 0.2)'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                }
            });
        });
    </script>

    @stack('js')
</body>

</html>

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

<body class="min-h-screen bg-gray-50 pb-20 md:pb-0 font-sans text-gray-800">

    <nav class="sticky top-0 z-50 shadow-lg glass">
        <div class="container px-6 py-4 mx-auto">
            <div class="flex items-center justify-between">

                <a href="{{ route('front') }}" class="flex items-center space-x-3 group">
                    <div class="flex items-center justify-center w-10 h-10 transition-transform duration-300 transform bg-[#5c4033] rounded-xl group-hover:rotate-12 shadow-md">
                        <i class="text-xl text-white fas fa-birthday-cake"></i>
                    </div>
                    <span class="text-2xl font-bold text-[#5c4033] tracking-tight">
                        Hana Cake
                    </span>
                </a>

                <div class="flex items-center space-x-4 md:hidden">
                    <div class="md:hidden">
                        <livewire:frontend.cart-counter />
                    </div>

                    @auth
                        <a href="{{ route('pelanggan.profile') }}" class="focus:outline-none">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                                alt="Avatar" class="object-cover w-8 h-8 border-2 border-[#eedcd3] rounded-full">
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#5c4033] focus:outline-none">
                            <i class="far fa-user text-2xl"></i>
                        </a>
                    @endauth
                </div>

                <div class="items-center hidden space-x-8 md:flex">
                    <a href="{{ route('ecommerce') }}" wire:navigate
                        class="relative font-bold text-gray-700 transition-colors hover:text-[#5c4033] group">
                        <span>Shop (PO)</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#5c4033] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('pelanggan.vouchers') }}" wire:navigate
                        class="relative font-bold text-gray-700 transition-colors hover:text-[#5c4033] group">
                        <span>Vouchers</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#5c4033] group-hover:w-full transition-all duration-300"></span>
                    </a>

                    <livewire:frontend.cart-counter />

                    @auth
                        @if(Auth::user()->role == 'pelanggan')

                            <div class="relative group" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="flex items-center space-x-2 font-bold text-gray-700 transition-colors hover:text-[#5c4033] focus:outline-none">

                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                                        alt="Avatar" class="object-cover w-8 h-8 border-2 border-[#eedcd3] rounded-full">

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
                                        class="flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-[#eedcd3]/30 hover:text-[#5c4033] transition-colors">
                                        <i class="w-5 mr-2 text-center text-gray-400 fas fa-user-edit"></i>Profil & Pengaturan
                                    </a>

                                    <a href="{{ route('pelanggan.my-orders') }}" wire:navigate
                                        class="flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-[#eedcd3]/30 hover:text-[#5c4033] transition-colors">
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
                                class="flex items-center px-5 py-2 text-sm font-bold text-white transition-all duration-300 rounded-full bg-[#5c4033] hover:shadow-lg hover:scale-105 hover:bg-[#4a3328]">
                                <i class="mr-2 fas fa-cash-register"></i>Masuk POS
                            </a>
                        @endif
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" wire:navigate
                                class="font-bold text-gray-600 transition-colors hover:text-[#5c4033]">
                                Login
                            </a>
                            <a href="{{ route('register') }}" wire:navigate
                                class="flex items-center px-5 py-2 font-bold text-white rounded-full shadow-md bg-[#5c4033] hover:bg-[#4a3328] transition-colors">
                                <i class="mr-2 text-sm fas fa-user-plus"></i> Register
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

    <footer class="py-12 mt-16 bg-[#5c4033] text-[#eedcd3]">
        <div class="container px-6 mx-auto">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="flex flex-col items-center mb-6 md:items-start md:mb-0">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-[#eedcd3] rounded-xl shadow-md">
                            <i class="text-2xl text-[#5c4033] fas fa-birthday-cake"></i>
                        </div>
                        <span class="text-3xl font-extrabold text-white tracking-tight">Hana Cake</span>
                    </div>
                    <p class="text-sm font-medium text-[#eedcd3]/80">Manisnya setiap momen, berawal dari sini.</p>
                </div>

                <div class="flex space-x-6">
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#eedcd3]/10 hover:bg-[#eedcd3] hover:text-[#5c4033] transition-all">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#eedcd3]/10 hover:bg-[#eedcd3] hover:text-[#5c4033] transition-all">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#eedcd3]/10 hover:bg-[#eedcd3] hover:text-[#5c4033] transition-all">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>
            
            <div class="w-full h-px bg-[#eedcd3]/20 my-8"></div>
            
            <div class="text-center text-sm font-medium text-[#eedcd3]/60">
                <p>© 2025 Hana Cake. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    @livewireScripts

    <!-- Bottom Navigation Bar (Mobile Only) -->
    @if(!request()->routeIs('pelanggan.checkout'))
    <div class="fixed bottom-0 left-0 right-0 z-[60] bg-[#eedcd3] shadow-[0_-4px_15px_rgba(0,0,0,0.05)] rounded-t-3xl md:hidden">
        <div class="flex justify-around items-center h-16">
            <a href="{{ route('front') }}" wire:navigate class="flex flex-col items-center justify-center w-16 h-12 {{ request()->routeIs('front') ? 'bg-[#5c4033] text-white rounded-xl shadow-md' : 'text-[#8b6f5e] hover:text-[#5c4033] transition-colors' }}">
                <i class="fas fa-home text-xl mb-0.5"></i>
                <span class="text-[10px] font-semibold {{ request()->routeIs('front') ? 'block' : 'hidden' }}">Home</span>
            </a>
            
            <a href="{{ route('pelanggan.vouchers') }}" wire:navigate class="flex flex-col items-center justify-center w-16 h-12 {{ request()->routeIs('pelanggan.vouchers') ? 'bg-[#5c4033] text-white rounded-xl shadow-md' : 'text-[#8b6f5e] hover:text-[#5c4033] transition-colors' }}">
                <i class="fas fa-ticket-alt text-xl mb-0.5"></i>
                <span class="text-[10px] font-semibold {{ request()->routeIs('pelanggan.vouchers') ? 'block' : 'hidden' }}">Promo</span>
            </a>
            
            <a href="{{ route('cart') }}" wire:navigate class="flex flex-col items-center justify-center w-16 h-12 {{ request()->routeIs('cart') ? 'bg-[#5c4033] text-white rounded-xl shadow-md' : 'text-[#8b6f5e] hover:text-[#5c4033] transition-colors relative' }}">
                <div class="relative">
                    <i class="fas fa-shopping-cart text-xl mb-0.5"></i>
                    @if(count(session()->get('cart', [])) > 0)
                        <span class="absolute -top-1 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-[#eedcd3]">
                            {{ array_sum(array_column(session()->get('cart', []), 'quantity')) }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] font-semibold {{ request()->routeIs('cart') ? 'block' : 'hidden' }}">Keranjang</span>
            </a>
            
            <a href="{{ route('pelanggan.profile') }}" wire:navigate class="flex flex-col items-center justify-center w-16 h-12 {{ request()->routeIs('pelanggan.profile') ? 'bg-[#5c4033] text-white rounded-xl shadow-md' : 'text-[#8b6f5e] hover:text-[#5c4033] transition-colors' }}">
                <i class="far fa-user text-xl mb-0.5"></i>
                <span class="text-[10px] font-semibold {{ request()->routeIs('pelanggan.profile') ? 'block' : 'hidden' }}">Profil</span>
            </a>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan dikembalikan ke halaman utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5c4033',
                cancelButtonColor: '#8b6f5e',
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
                        confirmButtonColor: '#5c4033',
                        cancelButtonColor: '#8b6f5e',
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

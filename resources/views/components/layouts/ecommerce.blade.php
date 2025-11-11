<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Hana Cake E-Commerce' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="font-sans bg-gray-100">

    <nav class="bg-white shadow-md">
        <div class="container flex items-center justify-between px-4 py-4 mx-auto">
            <a href="{{ route('ecommerce') }}" class="text-2xl font-bold text-indigo-600">Hana Cake</a>

            <div class="flex items-center space-x-6">
                <a href="{{ route('ecommerce') }}" class="text-gray-600 hover:text-indigo-600">Shop (PO)</a>

                <livewire:frontend.cart-counter />

                @auth
                    @if(Auth::user()->role == 'pelanggan')
                        <a href="{{ route('pelanggan.my-orders') }}" class="text-gray-600 hover:text-indigo-600">Pesanan
                            Saya</a>

                        <form method="POST" action="{{ route('pelanggan.logout') }}" id="logout-form" class="hidden">
                            @csrf
                        </form>

                        <button type="button" onclick="confirmLogout()"
                            class="text-gray-600 hover:text-indigo-600 focus:outline-none">
                            Logout
                        </button>

                    @else
                        <a href="{{ route('karyawan.pos') }}" class="text-sm text-red-500">Masuk ke POS</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container px-4 py-8 mx-auto">
        {{ $slot }}
    </main>

    @livewireScripts

    {{-- =============================================== --}}
    {{-- === PERBAIKAN DI SINI === --}}
    {{-- =============================================== --}}

    {{-- 1. Muat SweetAlert (langsung, bukan di push) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. Definisikan fungsi logout (langsung, bukan di push) --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan dikembalikan ke halaman utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    {{-- 3. Sediakan @stack('js') KOSONG --}}
    {{-- Ini adalah tempat untuk skrip dari halaman ANAK (seperti CheckoutPage) --}}
    @stack('js')

    {{-- =============================================== --}}
    {{-- === AKHIR PERBAIKAN === --}}
    {{-- =============================================== --}}
</body>

</html>

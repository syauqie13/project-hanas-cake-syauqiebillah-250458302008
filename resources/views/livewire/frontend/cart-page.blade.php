<div>
    <div class="min-h-screen pb-32 bg-gray-50 md:py-12 md:pb-12 md:bg-gradient-to-br md:from-purple-50 md:via-pink-50 md:to-blue-50">
        <div class="container px-3 mx-auto md:px-4">

            <header class="mt-4 mb-4 text-center md:mb-8">
                <h1 class="text-2xl font-bold text-transparent md:text-5xl bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                    Keranjang
                </h1>
                <p class="text-xs text-gray-500 md:text-lg md:text-gray-600">Periksa pesanan Anda sebelum checkout</p>
            </header>

            @if(empty($cartItems))
                <section class="max-w-2xl mx-auto animate-fadeIn">
                    <div class="p-8 text-center transition-transform duration-300 transform bg-white shadow-lg rounded-2xl hover:scale-105">
                        <div class="relative inline-block mb-4">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 blur-xl opacity-30 animate-pulse"></div>
                            <i class="relative z-10 text-6xl text-gray-300 fas fa-shopping-cart md:text-8xl"></i>
                        </div>
                        <h2 class="mb-2 text-xl font-bold text-gray-800 md:text-3xl">Keranjang Kosong</h2>
                        <p class="mb-6 text-sm text-gray-500 md:text-lg">Yuk mulai belanja kue spesial!</p>
                        <a href="{{ route('ecommerce') }}" wire:navigate
                            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-300 transform bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:shadow-xl md:text-base">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Mulai Belanja</span>
                        </a>
                    </div>
                </section>

            @else
                <div class="grid grid-cols-1 gap-4 lg:gap-8 lg:grid-cols-3 animate-fadeIn">

                    <section class="space-y-3 lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm md:shadow-xl rounded-xl md:rounded-2xl">

                            <div class="hidden px-6 py-4 md:block bg-gradient-to-r from-purple-600 to-pink-600">
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex items-center gap-3">
                                        <i class="text-xl fas fa-list-ul"></i>
                                        <h2 class="text-xl font-bold">Daftar Produk</h2>
                                    </div>
                                    <span class="px-4 py-1 text-sm font-semibold rounded-full bg-white/20 backdrop-blur-sm">
                                        {{ count($cartItems) }} Item
                                    </span>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($cartItems as $id => $item)
                                    <div wire:key="cart-{{ $id }}" class="p-3 transition-all duration-300 md:p-6 hover:bg-gray-50">
                                        <div class="flex gap-3 md:gap-6">

                                            <div class="relative flex-shrink-0">
                                                <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-lg md:w-32 md:h-32 md:rounded-2xl">
                                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/150x150/8b5cf6/ffffff?text=Kue' }}"
                                                        alt="{{ $item['name'] }}" class="object-cover w-full h-full">
                                                </div>
                                            </div>

                                            <div class="flex flex-col justify-between flex-1">
                                                <div>
                                                    <h3 class="text-sm font-bold text-gray-800 md:text-xl line-clamp-2 md:mb-2">
                                                        {{ $item['name'] }}
                                                    </h3>
                                                    <p class="text-sm font-bold text-purple-600 md:text-base">
                                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="flex items-end justify-between mt-2">

                                                    <div class="flex items-center h-8 border border-gray-300 rounded-lg md:h-10">
                                                        <button wire:click="updateQuantity('{{ $id }}', {{ max(1, $item['quantity'] - 1) }})"
                                                            class="flex items-center justify-center w-8 h-full text-gray-600 rounded-l-lg hover:bg-gray-100">
                                                            <i class="text-xs fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" value="{{ $item['quantity'] }}" readonly
                                                            class="w-10 h-full py-1 text-sm font-bold text-center text-gray-800 bg-transparent border-gray-200 focus:outline-none border-x">
                                                        <button wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})"
                                                            class="flex items-center justify-center w-8 h-full text-purple-600 rounded-r-lg hover:bg-gray-100">
                                                            <i class="text-xs fas fa-plus"></i>
                                                        </button>
                                                    </div>

                                                    <button wire:click="removeFromCart('{{ $id }}')"
                                                        class="p-2 text-gray-400 transition-colors hover:text-red-500">
                                                        <i class="text-lg fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <aside class="hidden lg:col-span-1 lg:block">
                        <div class="sticky overflow-hidden bg-white shadow-2xl top-24 rounded-2xl">
                            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600">
                                <h2 class="flex items-center gap-3 text-xl font-bold text-white">
                                    <i class="fas fa-receipt"></i> Ringkasan
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Total Item</span>
                                    <span class="font-bold">{{ count($cartItems) }} Produk</span>
                                </div>
                                <div class="flex items-baseline justify-between pt-2">
                                    <span class="text-lg font-bold text-gray-700">Total Bayar</span>
                                    <span class="text-3xl font-bold text-transparent bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>

                                @auth
                                    <a href="{{ route('pelanggan.checkout') }}"
                                        class="block w-full py-3 mt-4 font-bold text-center text-white transition-all rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:shadow-lg hover:-translate-y-1">
                                        Lanjut Checkout
                                    </a>
                                @else
                                    <button wire:click="showLoginWarning"
                                        class="block w-full py-3 mt-4 font-bold text-center text-white transition-all rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:shadow-lg">
                                        Login untuk Checkout
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </aside>

                </div>

                <div class="fixed bottom-0 left-0 right-0 z-40 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] lg:hidden animate-fadeIn">
                    <div class="container flex items-center justify-between gap-4 mx-auto">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Total Pembayaran</span>
                            <span class="text-xl font-bold text-purple-700">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        @auth
                            <a href="{{ route('pelanggan.checkout') }}"
                                class="px-8 py-3 text-sm font-bold text-white shadow-lg bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:opacity-90">
                                Checkout
                            </a>
                        @else
                            <button wire:click="showLoginWarning"
                                class="px-8 py-3 text-sm font-bold text-white shadow-lg bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:opacity-90">
                                Checkout
                            </button>
                        @endauth
                    </div>
                </div>

            @endif
        </div>
    </div>

    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.4s ease-out; }

        /* No Spin Buttons */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }
    </style>
</div>

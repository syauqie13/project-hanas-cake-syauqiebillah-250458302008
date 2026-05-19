<div>
    <div class="min-h-screen pb-40 bg-[#fcfcfc] md:bg-[#f8f9fa] md:py-10">
        <div class="container px-4 mx-auto max-w-5xl">

            <header class="pt-6 mb-6 md:mb-10 flex flex-col items-center justify-center">
                <h1 class="text-xl md:text-3xl font-bold text-[#4a3328]">Keranjang Saya</h1>
                <p class="text-[11px] md:text-sm text-gray-500 mt-1">Periksa pesanan Anda sebelum checkout</p>
            </header>

            @if(empty($cartItems))
                <section class="max-w-md mx-auto animate-fadeIn mt-10">
                    <div class="p-8 text-center bg-white border border-gray-100 shadow-sm md:shadow-md rounded-3xl">
                        <div class="w-24 h-24 mx-auto mb-6 bg-[#eedcd3] rounded-full flex items-center justify-center">
                            <i class="text-4xl text-[#5c4033] fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="mb-2 text-lg font-bold text-gray-800">Keranjang Masih Kosong</h2>
                        <p class="mb-8 text-xs text-gray-500">Yuk mulai pilih dan belanja kue spesial kesukaanmu!</p>
                        <a href="{{ route('ecommerce') }}" wire:navigate
                            class="inline-flex items-center justify-center w-full gap-2 px-6 py-3.5 text-sm font-bold text-white transition-all bg-[#5c4033] rounded-xl hover:bg-[#4a3328] shadow-md shadow-amber-900/10">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Mulai Belanja</span>
                        </a>
                    </div>
                </section>

            @else
                <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-3 animate-fadeIn">

                    <section class="space-y-4 lg:col-span-2">
                        <div class="hidden lg:flex items-center justify-between pb-4 border-b border-gray-200">
                            <h2 class="text-lg font-bold text-[#4a3328]">Daftar Produk</h2>
                            <span class="px-4 py-1 text-xs font-bold text-[#5c4033] bg-[#eedcd3] rounded-full">
                                {{ count($cartItems) }} Item
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($cartItems as $id => $item)
                                <div wire:key="cart-{{ $id }}" class="bg-white p-4 md:p-5 border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex gap-4">
                                        
                                        <div class="w-20 h-20 md:w-28 md:h-28 overflow-hidden bg-gray-100 rounded-xl shrink-0">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/150x150/eedcd3/5c4033?text=Kue' }}"
                                                alt="{{ $item['name'] }}" class="object-cover w-full h-full">
                                        </div>

                                        <div class="flex flex-col justify-between flex-1">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h3 class="text-sm md:text-base font-bold text-gray-800 line-clamp-2 leading-snug">
                                                        {{ $item['name'] }}
                                                    </h3>
                                                    <p class="text-[13px] md:text-sm font-bold text-[#5c4033] mt-1">
                                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                
                                                <button wire:click="removeFromCart('{{ $id }}')" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                                    <i class="text-sm md:text-base far fa-trash-alt"></i>
                                                </button>
                                            </div>

                                            <div class="flex items-center mt-3 w-max bg-[#f8f9fa] border border-gray-200 rounded-lg h-8 md:h-9">
                                                <button wire:click="updateQuantity('{{ $id }}', {{ max(1, $item['quantity'] - 1) }})"
                                                    class="flex items-center justify-center w-8 md:w-9 h-full text-gray-500 hover:text-[#5c4033] transition-colors rounded-l-lg">
                                                    <i class="text-[10px] fas fa-minus"></i>
                                                </button>
                                                
                                                <input type="number" value="{{ $item['quantity'] }}" readonly
                                                    class="w-10 h-full py-1 text-xs md:text-sm font-bold text-center text-gray-800 bg-transparent border-x border-gray-200 focus:outline-none pointer-events-none">
                                                
                                                <button wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})"
                                                    class="flex items-center justify-center w-8 md:w-9 h-full text-gray-500 hover:text-[#5c4033] transition-colors rounded-r-lg">
                                                    <i class="text-[10px] fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <aside class="hidden lg:col-span-1 lg:block">
                        <div class="sticky top-24 bg-white border border-gray-100 shadow-xl rounded-3xl overflow-hidden">
                            <div class="p-6 pb-4 border-b border-gray-100 bg-gray-50/50">
                                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Ringkasan Belanja</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between pb-4 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Total Item</span>
                                    <span class="text-sm font-bold text-gray-800">{{ count($cartItems) }} Produk</span>
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-base font-bold text-gray-800">Total Bayar</span>
                                    <span class="text-2xl font-bold text-[#5c4033]">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>

                                @auth
                                    <a href="{{ route('pelanggan.checkout') }}"
                                        class="flex items-center justify-center w-full py-3.5 mt-6 text-sm font-bold text-white transition-all rounded-xl bg-[#1c6b38] hover:bg-[#15532b] shadow-lg shadow-green-900/10">
                                        Lanjut Checkout
                                    </a>
                                @else
                                    <button wire:click="showLoginWarning"
                                        class="flex items-center justify-center w-full py-3.5 mt-6 text-sm font-bold text-white transition-all rounded-xl bg-[#5c4033] hover:bg-[#4a3328] shadow-md">
                                        Login untuk Checkout
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </aside>

                </div>

                <div class="fixed bottom-[70px] left-0 right-0 z-40 p-4 bg-white border-t border-gray-100 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] lg:hidden animate-fadeIn">
                    <div class="container flex items-center justify-between gap-4 mx-auto max-w-md">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-500 font-medium">Total Pembayaran</span>
                            <span class="text-[17px] font-bold text-[#5c4033]">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        @auth
                            <a href="{{ route('pelanggan.checkout') }}"
                                class="px-8 py-3 text-[13px] font-bold text-white shadow-md bg-[#1c6b38] rounded-xl hover:bg-[#15532b] active:scale-95 transition-all">
                                Checkout
                            </a>
                        @else
                            <button wire:click="showLoginWarning"
                                class="px-6 py-3 text-[13px] font-bold text-white shadow-md bg-[#5c4033] rounded-xl hover:bg-[#4a3328] active:scale-95 transition-all">
                                Login Dulu
                            </button>
                        @endauth
                    </div>
                </div>

            @endif
        </div>
    </div>

    <style>
        /* Animasi Transisi Halus */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }

        /* Menyembunyikan panah atas-bawah pada input number */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }
    </style>
</div>
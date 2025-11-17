<div>
    <div
        class="min-h-screen py-6 bg-gray-50 md:py-12 md:bg-gradient-to-br md:from-purple-50 md:via-pink-50 md:to-blue-50">
        <div class="max-w-6xl px-4 mx-auto">

            <div class="mb-6 md:mb-10">
                <div class="flex items-center gap-3 mb-4 md:gap-4">
                    <div
                        class="flex items-center justify-center w-12 h-12 shadow-lg md:w-16 md:h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl md:rounded-2xl">
                        <i class="text-lg text-white md:text-2xl fas fa-history"></i>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold text-transparent md:text-5xl bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                            Riwayat Pesanan
                        </h1>
                        <p class="mt-1 text-xs text-gray-600 md:text-base">Pantau status pesanan Pre-Order Anda</p>
                    </div>
                </div>
            </div>

            @forelse($orders as $order)
                <div wire:key="order-{{ $order->id }}"
                    class="mb-6 bg-white rounded-2xl md:rounded-3xl shadow-md md:shadow-2xl overflow-hidden border border-gray-100 md:border-2 md:border-transparent md:transform md:hover:scale-[1.02] transition-all duration-300">

                    <div class="px-4 py-4 md:px-6 md:py-5 bg-gradient-to-r from-purple-600 to-pink-600">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                            <div class="flex items-center justify-between md:justify-start md:gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 rounded-lg md:w-14 md:h-14 bg-white/20 backdrop-blur-sm md:rounded-xl">
                                        <i class="text-lg text-white md:text-xl fas fa-receipt"></i>
                                    </div>
                                    <div class="text-white">
                                        <div class="text-[10px] md:text-sm font-medium opacity-90">Order ID</div>
                                        <div class="text-lg font-bold md:text-2xl">
                                            #{{ $order->merchant_order_id ?? $order->id }}</div>
                                    </div>
                                </div>

                                <div class="text-right text-white md:hidden opacity-90">
                                    <div class="text-[10px] flex items-center justify-end gap-1">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $order->tanggal->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px]">{{ $order->tanggal->format('H:i') }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 mt-1 md:flex-col md:items-end md:mt-0">
                                <div class="items-center hidden gap-2 mb-1 text-xs text-white opacity-75 md:flex">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $order->tanggal->format('d M Y, H:i') }}
                                </div>

                                @if($order->payment_status == 'pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs md:text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                        <i class="mr-1.5 fas fa-hourglass-half"></i> Menunggu Bayar
                                    </span>
                                @elseif($order->status == 'processing')
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1 md:px-5 md:py-2.5 bg-blue-500 text-white rounded-full font-bold shadow-sm text-xs md:text-base">
                                        <i class="fas fa-fire-alt"></i> <span>Diproses</span>
                                    </div>
                                @elseif($order->status == 'completed')
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1 md:px-5 md:py-2.5 bg-green-500 text-white rounded-full font-bold shadow-sm text-xs md:text-base">
                                        <i class="fas fa-check-circle"></i> <span>Selesai</span>
                                    </div>
                                @elseif($order->status == 'shipped')
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1 md:px-5 md:py-2.5 bg-amber-500 text-white rounded-full font-bold shadow-sm text-xs md:text-base">
                                        <i class="fas fa-truck"></i> <span>Dikirim</span>
                                    </div>
                                @elseif($order->payment_status == 'paid')
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1 md:px-5 md:py-2.5 bg-green-500 text-white rounded-full font-bold shadow-sm text-xs md:text-base">
                                        <i class="fas fa-money-bill-wave"></i> <span>Lunas</span>
                                    </div>
                                @else
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1 md:px-5 md:py-2.5 bg-red-500 text-white rounded-full font-bold shadow-sm text-xs md:text-base">
                                        <i class="fas fa-times-circle"></i> <span>Batal</span>
                                    </div>
                                @endif

                                @if($order->payment_status == 'pending')
    <!-- Mobile -->
    <a href="{{ route('pelanggan.pay', $order->id) }}"
       class="md:hidden inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-white
              bg-gradient-to-r from-purple-500 to-pink-500 rounded-full shadow-md
              hover:shadow-lg hover:scale-[1.03] active:scale-[0.98]
              transition-all duration-200">
        Bayar
    </a>

    <!-- Desktop -->
    <a href="{{ route('pelanggan.pay', $order->id) }}"
       class="hidden md:inline-flex items-center gap-2 mt-1 text-sm font-semibold text-white
              bg-gradient-to-r from-purple-600 to-pink-600 px-5 py-2 rounded-xl shadow-sm
              hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
        Bayar Sekarang
    </a>
@endif

                            </div>
                        </div>
                    </div>

                    <div class="p-4 md:p-6">
                        <h3 class="items-center hidden gap-2 mb-4 text-lg font-bold text-gray-800 md:flex">
                            <i class="text-purple-500 fas fa-box-open"></i> Detail Pesanan
                        </h3>

                        <div class="space-y-3 md:space-y-4">
                            @foreach($order->items as $item)
                                <div
                                    class="flex items-start justify-between p-3 border border-gray-100 md:items-center md:p-4 bg-gray-50 md:bg-gradient-to-r md:from-purple-50 md:to-pink-50 rounded-xl md:border-0">
                                    <div class="flex items-start flex-1 gap-3 md:items-center md:gap-4">

                                        <div class="relative flex-shrink-0">
                                            <div
                                                class="w-16 h-16 overflow-hidden bg-white rounded-lg shadow-sm md:w-20 md:h-20 md:rounded-xl">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100/8b5cf6/ffffff?text=Kue' }}"
                                                    alt="{{ $item->product->name }}" class="object-cover w-full h-full">
                                            </div>
                                            <div
                                                class="absolute flex items-center justify-center text-[10px] md:text-xs font-bold text-white rounded-full shadow-md -top-2 -right-2 w-5 h-5 md:w-7 md:h-7 bg-gradient-to-r from-purple-500 to-pink-500">
                                                {{ $item->jumlah }}
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="mb-1 text-xs font-bold text-gray-800 md:text-base line-clamp-2">
                                                {{ $item->product->name ?? 'Produk Tidak Tersedia' }}
                                            </h4>
                                            <div
                                                class="flex flex-col gap-1 text-xs text-gray-600 md:flex-row md:items-center md:gap-2 md:text-sm">
                                                <span class="hidden px-3 py-1 font-medium bg-white rounded-lg md:inline">
                                                    {{ $item->jumlah }} × Rp
                                                    {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                                </span>
                                                <span class="text-gray-500 md:hidden">
                                                    {{ $item->jumlah }} x Rp
                                                    {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ml-2 text-right">
                                        <div class="hidden mb-1 text-sm text-gray-500 md:block">Subtotal</div>
                                        <div
                                            class="text-sm font-bold text-purple-700 md:text-xl md:text-transparent md:bg-gradient-to-r md:from-purple-600 md:to-pink-600 md:bg-clip-text">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="px-4 py-4 border-t border-gray-200 md:px-6 md:py-5 bg-gray-50 md:bg-gradient-to-r md:from-gray-50 md:to-gray-100">
                        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">

                            @if($order->shipping_address)
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="items-center justify-center flex-shrink-0 hidden w-10 h-10 md:flex bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl">
                                            <i class="text-white fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="text-xs text-gray-600 md:text-sm">
                                            <div class="flex items-center gap-2 mb-1 font-bold text-gray-800">
                                                <i class="text-purple-500 fas fa-map-marker-alt md:hidden"></i>
                                                Dikirim ke: <span class="text-gray-900">{{ $order->shipping_name }}</span>
                                            </div>
                                            <p class="line-clamp-1 md:line-clamp-none">{{ $order->shipping_address }}</p>
                                            <p>{{ $order->shipping_city }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div
                                class="pt-3 border-t border-gray-200 md:pt-0 md:border-0 md:p-5 md:bg-white md:border-2 md:border-purple-100 md:shadow-lg md:rounded-2xl">
                                <div class="flex items-center justify-between md:block md:text-center">
                                    <div class="text-sm font-bold text-gray-600 md:mb-2 md:font-semibold">Total Tagihan
                                    </div>
                                    <div
                                        class="text-lg font-bold text-transparent md:text-4xl bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-4 py-3 overflow-x-auto border-t border-purple-100 md:px-6 md:py-4 bg-purple-50/50 hide-scrollbar">
                        <div class="flex items-center min-w-max md:min-w-0 md:justify-center gap-3 text-[10px] md:text-sm">

                            <div
                                class="flex items-center gap-1.5 {{ $order->payment_status == 'paid' || $order->status == 'pending' ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas fa-check-circle"></i> <span class="font-medium">Dibuat</span>
                            </div>
                            <div
                                class="w-8 md:w-12 h-0.5 {{ $order->payment_status == 'paid' ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>

                            <div
                                class="flex items-center gap-1.5 {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas fa-credit-card"></i> <span class="font-medium">Dibayar</span>
                            </div>
                            <div
                                class="w-8 md:w-12 h-0.5 {{ $order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>

                            <div
                                class="flex items-center gap-1.5 {{ $order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed' ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas fa-cog {{ $order->status == 'processing' ? 'fa-spin' : '' }}"></i> <span
                                    class="font-medium">Diproses</span>
                            </div>
                            <div
                                class="w-8 md:w-12 h-0.5 {{ $order->status == 'shipped' || $order->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>

                            <div
                                class="flex items-center gap-1.5 {{ $order->status == 'completed' ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas fa-flag-checkered"></i> <span class="font-medium">Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-white shadow-lg md:p-16 md:shadow-2xl rounded-2xl md:rounded-3xl">
                    <div class="max-w-md mx-auto">
                        <div class="relative inline-block mb-4 md:mb-6">
                            <div
                                class="absolute inset-0 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 blur-2xl md:blur-3xl opacity-30 animate-pulse">
                            </div>
                            <i class="relative z-10 text-6xl text-gray-300 fas fa-shopping-bag md:text-9xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-800 md:mb-3 md:text-3xl">Belum Ada Pesanan</h3>
                        <p class="mb-6 text-sm text-gray-500 md:mb-8 md:text-lg">
                            Yuk mulai pesan kue spesial untuk momen istimewa Anda!
                        </p>
                        <a href="{{ route('ecommerce') }}" wire:navigate
                            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-300 transform shadow-lg md:gap-3 md:px-8 md:py-4 md:text-base bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl md:rounded-2xl hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Mulai Belanja</span>
                        </a>
                    </div>
                </div>
            @endforelse

            <div class="mt-6 overflow-x-auto md:mt-10">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <style>
        /* Hide scrollbar for timeline mobile */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>

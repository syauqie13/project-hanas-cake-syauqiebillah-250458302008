<div>
    <div class="min-h-screen py-6 md:py-12 bg-[#fcfcfc] md:bg-[#f8f9fa]">
        <div class="max-w-4xl px-4 mx-auto">

            <div class="flex items-center gap-4 mb-6 md:mb-10">
                <div class="flex items-center justify-center shrink-0 w-12 h-12 md:w-16 md:h-16 bg-[#eedcd3] rounded-full md:rounded-2xl shadow-sm">
                    <i class="text-xl md:text-2xl text-[#5c4033] fas fa-history"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-4xl font-bold text-[#4a3328] tracking-tight">
                        Riwayat Pesanan
                    </h1>
                    <p class="mt-0.5 text-xs md:text-sm text-gray-500">Pantau status pesanan Pre-Order Anda di sini</p>
                </div>
            </div>

            @forelse($orders as $order)
                <div wire:key="order-{{ $order->id }}"
                    class="mb-6 bg-white rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden md:transform md:hover:-translate-y-1 transition-all duration-300">

                    <div class="p-4 md:px-6 md:py-5 bg-[#5c4033]">
                        <div class="flex justify-between items-start md:items-center">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 shrink-0">
                                    <i class="text-white fas fa-receipt"></i>
                                </div>
                                <div>
                                    <p class="text-white/80 text-[10px] md:text-xs font-medium uppercase tracking-wider mb-0.5">Order ID</p>
                                    <p class="text-white font-bold text-sm md:text-lg tracking-wide">
                                        #{{ $order->merchant_order_id ?? $order->id }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right text-white opacity-90">
                                <p class="text-[10px] md:text-xs font-medium mb-0.5"><i class="far fa-calendar-alt mr-1"></i>{{ $order->tanggal->format('d M Y') }}</p>
                                <p class="text-[10px] md:text-xs">{{ $order->tanggal->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/10">
                            
                            <div>
                                @if($order->payment_status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-[#5c4033] bg-[#eedcd3] rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-hourglass-half"></i> Menunggu Bayar
                                    </span>
                                @elseif($order->status == 'processing')
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-white bg-blue-500 rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-fire-alt"></i> Diproses
                                    </span>
                                @elseif($order->status == 'completed')
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-white bg-green-500 rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-check-circle"></i> Selesai
                                    </span>
                                @elseif($order->status == 'shipped')
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-white bg-amber-500 rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-truck"></i> Dikirim
                                    </span>
                                @elseif($order->payment_status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-white bg-[#1c6b38] rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-money-bill-wave"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 text-[11px] md:text-sm font-bold text-white bg-red-500 rounded-full shadow-sm">
                                        <i class="mr-1.5 fas fa-times-circle"></i> Batal
                                    </span>
                                @endif
                            </div>

                            @if($order->payment_status == 'pending')
                                <a href="{{ route('pelanggan.pay', $order->id) }}"
                                   class="inline-flex items-center gap-1.5 px-5 py-2 text-xs md:text-sm font-bold text-white bg-[#1c6b38] rounded-full shadow-md hover:bg-[#15532b] active:scale-95 transition-all">
                                    <i class="fas fa-wallet"></i> Bayar
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 md:p-6 bg-white">
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Daftar Produk</h3>
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    
                                    <div class="relative w-16 h-16 md:w-20 md:h-20 shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100/eedcd3/5c4033?text=Kue' }}"
                                            alt="{{ $item->product->name }}" class="object-cover w-full h-full">
                                        <div class="absolute top-0 right-0 flex items-center justify-center text-[10px] font-bold text-white bg-[#5c4033] w-5 h-5 rounded-bl-xl shadow-sm">
                                            {{ $item->jumlah }}
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                                        <h4 class="text-sm md:text-base font-bold text-gray-800 line-clamp-1 mb-0.5">
                                            {{ $item->product->name ?? 'Produk Tidak Tersedia' }}
                                        </h4>
                                        <p class="text-[11px] md:text-sm text-gray-500">
                                            {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm md:text-base font-bold text-[#5c4033] mt-1">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 md:p-6 bg-[#fcfaf8] border-t border-gray-100">
                        @if($order->shipping_address)
                            <div class="flex items-start gap-3 mb-4 pb-4 border-b border-gray-200/60">
                                <i class="text-[#5c4033] fas fa-map-marker-alt mt-0.5"></i>
                                <div class="text-xs md:text-sm text-gray-600">
                                    <p class="font-bold text-gray-800 mb-0.5">Dikirim ke: {{ $order->shipping_name }}</p>
                                    <p class="leading-relaxed line-clamp-2">{{ $order->shipping_address }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-xs md:text-sm font-bold text-gray-500 uppercase tracking-widest">Total Tagihan</span>
                            <span class="text-lg md:text-2xl font-bold text-[#1c6b38]">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="px-4 py-5 bg-white border-t border-gray-100">
                        <div class="flex items-start justify-between w-full">

                            <div class="flex flex-col items-center gap-1.5 w-[50px] md:w-20">
                                <div class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full {{ $order->payment_status == 'paid' || $order->status == 'pending' ? 'bg-[#1c6b38] text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                    <i class="fas fa-clipboard-list text-[10px] md:text-sm"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold text-center leading-tight {{ $order->payment_status == 'paid' || $order->status == 'pending' ? 'text-[#1c6b38]' : 'text-gray-400' }}">Dibuat</span>
                            </div>

                            <div class="flex-1 h-0.5 mt-3 md:mt-4 mx-1 md:mx-2 {{ $order->payment_status == 'paid' ? 'bg-[#1c6b38]' : 'bg-gray-100' }}"></div>

                            <div class="flex flex-col items-center gap-1.5 w-[50px] md:w-20">
                                <div class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full {{ $order->payment_status == 'paid' ? 'bg-[#1c6b38] text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                    <i class="fas fa-wallet text-[10px] md:text-sm"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold text-center leading-tight {{ $order->payment_status == 'paid' ? 'text-[#1c6b38]' : 'text-gray-400' }}">Dibayar</span>
                            </div>

                            <div class="flex-1 h-0.5 mt-3 md:mt-4 mx-1 md:mx-2 {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-[#1c6b38]' : 'bg-gray-100' }}"></div>

                            <div class="flex flex-col items-center gap-1.5 w-[50px] md:w-20">
                                <div class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-[#1c6b38] text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                    <i class="fas fa-fire-alt text-[10px] md:text-sm {{ $order->status == 'processing' ? 'animate-pulse' : '' }}"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold text-center leading-tight {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-[#1c6b38]' : 'text-gray-400' }}">Diproses</span>
                            </div>

                            <div class="flex-1 h-0.5 mt-3 md:mt-4 mx-1 md:mx-2 {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-[#1c6b38]' : 'bg-gray-100' }}"></div>

                            <div class="flex flex-col items-center gap-1.5 w-[50px] md:w-20">
                                <div class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full {{ $order->status == 'completed' ? 'bg-[#1c6b38] text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                    <i class="fas fa-flag-checkered text-[10px] md:text-sm"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold text-center leading-tight {{ $order->status == 'completed' ? 'text-[#1c6b38]' : 'text-gray-400' }}">Selesai</span>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-3xl mt-10">
                    <div class="w-24 h-24 mx-auto bg-[#eedcd3] rounded-full flex items-center justify-center mb-6">
                        <i class="text-5xl text-[#5c4033] fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-[#4a3328]">Belum Ada Pesanan</h3>
                    <p class="mb-8 text-xs text-gray-500">Yuk mulai pesan kue spesial untuk momen istimewa Anda!</p>
                    <a href="{{ route('ecommerce') }}" wire:navigate
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-white transition-all bg-[#5c4033] rounded-xl hover:bg-[#4a3328] shadow-md active:scale-95">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Mulai Belanja</span>
                    </a>
                </div>
            @endforelse

            <div class="mt-8 overflow-x-auto">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
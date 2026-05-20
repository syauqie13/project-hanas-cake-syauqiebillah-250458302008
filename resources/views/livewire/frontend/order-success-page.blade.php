<div class="w-full min-h-screen bg-gray-50 pb-20 font-sans text-gray-800 antialiased">
    
    <!-- Top Premium Success Header -->
    <div class="bg-gradient-to-b from-[#5c4033] to-[#4a3328] pt-12 pb-16 px-6 text-center relative overflow-hidden rounded-b-[2.5rem] shadow-md">
        
        <!-- Subtle background glowing effects -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>

        <!-- 3D Gold/Brown Receipt Icon -->
        <div class="mx-auto w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center shadow-lg border border-white/20 mb-6 transform hover:scale-105 transition-transform duration-300">
            <div class="relative">
                <i class="fas fa-receipt text-4xl text-amber-200 animate-bounce"></i>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                </span>
            </div>
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-wide">
            Payment Success
        </h1>
        <p class="text-xs text-amber-100/70 mt-1 font-medium">Pesanan Anda berhasil dikonfirmasi oleh sistem</p>
    </div>

    <!-- Main Content Box -->
    <div class="max-w-md mx-auto -mt-8 px-4">
        
        <!-- Card Container -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_10px_30px_rgba(0,0,0,0.04)] border border-gray-100/80 space-y-8">
            
            <!-- Store Selection / Distance Details -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <!-- Lavender/Blue Circle Icon exactly like mockup -->
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                    <i class="fas fa-store text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 truncate">
                        {{ $store ? $store->name : 'Hana\'s Cake Store' }}
                    </h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1.5">
                        @if($distance !== null)
                            <span>{{ $distance }} km</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-indigo-600 font-bold">Terdekat</span>
                        @else
                            <span>{{ $store ? $store->address : 'Ambil di Toko' }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Huge Queue / Pickup Number -->
            <div class="text-center py-6 space-y-4">
                <span class="text-[10px] font-bold text-[#8b6f5e] uppercase tracking-widest bg-[#f4dfd4]/40 px-4 py-1.5 rounded-full">
                    Nomor Antrean Anda
                </span>
                
                <h2 class="text-7xl md:text-8xl font-black text-[#4a3328] tracking-widest font-serif block py-2 select-all">
                    {{ $queueNumber }}
                </h2>
                
                <p class="text-xs md:text-sm text-[#8b6f5e] font-semibold max-w-[280px] mx-auto leading-relaxed">
                    Tunjukkan halaman ini kepada kasir untuk ambil pesanan.
                </p>
            </div>

            <!-- Details Summary Table -->
            <div class="border-t border-dashed border-gray-200 pt-5 space-y-3 text-xs">
                <div class="flex justify-between items-center text-gray-400">
                    <span>ID Transaksi</span>
                    <span class="font-bold text-gray-700">{{ $order->merchant_order_id ?? '#'.$order->id }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-400">
                    <span>Metode Pengambilan</span>
                    <span class="font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">Pick Up (Ambil di Toko)</span>
                </div>
                <div class="flex justify-between items-center text-gray-400">
                    <span>Total Pembayaran</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action Buttons exactly like mockup -->
            <div class="space-y-3 pt-2">
                <!-- Hubugi CS Button -->
                <a href="https://wa.me/6281234567890?text=Halo%20Hana's%20Cake,%20saya%20ingin%20konfirmasi%20pengambilan%20pesanan%20dengan%20nomor%20antrean%20%23{{ $queueNumber }}%20(Order%20ID:%20{{ $order->merchant_order_id ?? $order->id }})"
                   target="_blank"
                   class="w-full bg-[#5c4033] text-white py-4 rounded-2xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-[#4a3328] active:scale-[0.98] transition-all shadow-md shadow-[#5c4033]/15">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Hubugi CS</span>
                </a>

                <!-- Back to Home Button -->
                <a href="{{ route('front') }}" wire:navigate
                   class="w-full bg-white border-2 border-gray-200 text-gray-700 py-4 rounded-2xl text-sm font-bold flex items-center justify-center hover:bg-gray-50 active:scale-[0.98] transition-all">
                    <span>Back to Home</span>
                </a>
            </div>

        </div>

    </div>

</div>

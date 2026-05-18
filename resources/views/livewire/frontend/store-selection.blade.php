<div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-20 md:max-w-4xl md:mt-8 md:rounded-3xl md:shadow-xl overflow-hidden md:border md:border-gray-100 relative">
    
    <!-- Global Loading Indicator -->
    <div wire:loading class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
        <div class="h-full bg-[#5c4033] animate-pulse w-1/3 rounded-r-full"></div>
    </div>

    <!-- Header -->
    <div class="bg-white px-4 py-4 flex items-center justify-between border-b border-gray-100 sticky top-0 z-20">
        <a href="{{ route('ecommerce', ['mode' => $mode]) }}" wire:navigate class="w-8 h-8 flex items-center justify-center text-[#5c4033] hover:bg-[#f4dfd4] rounded-full transition">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="text-[#5c4033] font-bold text-lg">Hana's Cake</h1>
        <button class="w-8 h-8 flex items-center justify-center text-[#5c4033] hover:bg-[#f4dfd4] rounded-full transition">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Active Mode Selector -->
    <div class="bg-white px-4 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-[#f4dfd4] bg-[#f4dfd4]/30 flex items-center justify-center relative">
                
                <!-- Spinner saat ganti mode -->
                <div wire:loading wire:target="mode" class="absolute inset-0 bg-white/70 flex items-center justify-center z-10">
                    <i class="fas fa-spinner fa-spin text-[#5c4033] text-sm"></i>
                </div>

                @if($mode == 'pickup')
                    <img src="{{ asset('images/pickup.png') }}" class="w-10 h-10 object-contain drop-shadow-sm origin-bottom" alt="Pick Up">
                @else
                    <img src="{{ asset('images/delivery.png') }}" class="w-10 h-10 object-contain drop-shadow-sm origin-bottom" alt="Delivery">
                @endif
            </div>
            <h2 class="text-[#5c4033] font-bold text-xl">{{ $mode == 'pickup' ? 'Pick Up' : 'Delivery' }}</h2>
        </div>
        <button wire:click="$set('mode', '{{ $mode == 'pickup' ? 'delivery' : 'pickup' }}')" wire:loading.attr="disabled" class="border border-[#5c4033] text-[#5c4033] rounded-full px-4 py-1.5 text-xs font-semibold hover:bg-[#5c4033] hover:text-white transition disabled:opacity-50">
            <span wire:loading.remove wire:target="mode">Ubah ke {{ $mode == 'pickup' ? 'Delivery' : 'Pick Up' }}</span>
            <span wire:loading wire:target="mode"><i class="fas fa-sync fa-spin mr-1"></i> Proses...</span>
        </button>
    </div>

    <div class="px-4 py-3 bg-white border-b border-gray-100 flex items-center justify-between">
        <span class="text-sm font-bold text-gray-500">{{ $stores->count() }} Store Tersedia</span>
        
        <!-- Indikator Loading Geolocation -->
        <span wire:loading wire:target="updateLocation" class="text-xs text-[#5c4033] font-medium flex items-center gap-1 animate-pulse">
            <i class="fas fa-location-arrow"></i> Mencari lokasi...
        </span>
    </div>

    <!-- Store List -->
    <div class="flex flex-col gap-3 p-3">
        @forelse($stores as $index => $store)
            <!-- Ubah warna border & background saat selected menjadi coklat (tema Hana's Cake) -->
            <div wire:click="selectStore({{ $store->id }})" class="bg-white p-5 rounded-2xl border-2 {{ session('selected_store_id') == $store->id ? 'border-[#5c4033] bg-[#f4dfd4]/20' : 'border-transparent shadow-sm' }} flex flex-col hover:shadow-md hover:border-[#5c4033]/30 transition-all cursor-pointer relative overflow-hidden group">
                
                <!-- Loading overlay saat memilih toko -->
                <div wire:loading wire:target="selectStore({{ $store->id }})" class="absolute inset-0 bg-white/50 backdrop-blur-sm flex items-center justify-center z-10">
                    <i class="fas fa-circle-notch fa-spin text-[#5c4033] text-3xl"></i>
                </div>

                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-gray-800 font-bold text-lg leading-tight pr-4 group-hover:text-[#5c4033] transition-colors">
                        {{ $store->name }}
                    </h3>
                    @if($index === 0 && $store->distance !== null)
                        <div class="bg-[#5c4033] text-white px-2 py-1 rounded-md text-[10px] font-bold flex items-center gap-1 shrink-0 shadow-sm">
                            <i class="fas fa-star text-yellow-300"></i> Terdekat
                        </div>
                    @endif
                </div>
                
                <p class="text-xs text-gray-500 mb-2 leading-relaxed">{{ $store->address ?? "Alamat detail belum ditambahkan." }}</p>
                
                <p class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                    @if($store->distance !== null)
                        <span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center text-red-500"><i class="fas fa-map-marker-alt text-[10px]"></i></span>
                        {{ $store->distance }} km dari lokasimu 
                    @else
                        <span class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-map-marker-alt text-[10px]"></i></span>
                        Menghitung jarak...
                    @endif
                </p>

                <div class="flex items-center gap-4 mb-4 border-b border-dashed border-gray-200 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded bg-[#f4dfd4]/50 flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-[#5c4033] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-600 font-medium">Pick Up</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded bg-[#f4dfd4]/50 flex items-center justify-center">
                            <i class="fas fa-motorcycle text-[#5c4033] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-600 font-medium">Delivery</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm mt-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">Buka</span>
                        <span class="text-gray-600 font-medium text-xs">
                            <i class="far fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($store->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($store->close_time)->format('H:i') }}
                        </span>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-gray-50 group-hover:bg-[#5c4033] group-hover:text-white flex items-center justify-center transition-colors">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center bg-white rounded-2xl border border-gray-100">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-store-slash text-2xl text-gray-400"></i>
                </div>
                <h4 class="font-bold text-gray-700 mb-1">Store Belum Tersedia</h4>
                <p class="text-gray-500 text-xs">Mohon maaf, saat ini belum ada cabang yang aktif.</p>
            </div>
        @endforelse
    </div>

    <!-- Geolocation Script dengan format Livewire 3 yang lebih aman -->
    @script
    <script>
        if (! $wire.userLat) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        $wire.updateLocation(position.coords.latitude, position.coords.longitude);
                    }, 
                    (error) => {
                        console.error("Akses lokasi ditolak / gagal: ", error);
                        // Fallback simulasi
                        $wire.updateLocation(-6.402484, 106.973495);
                    }, 
                    {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            }
        }
    </script>
    @endscript
</div>
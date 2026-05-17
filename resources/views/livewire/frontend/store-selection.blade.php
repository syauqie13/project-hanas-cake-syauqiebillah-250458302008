<div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-20 md:max-w-4xl md:mt-8 md:rounded-3xl md:shadow-xl overflow-hidden md:border md:border-gray-100">
    <!-- Header -->
    <div class="bg-white px-4 py-4 flex items-center justify-between border-b border-gray-100 sticky top-0 z-20">
        <a href="{{ route('ecommerce', ['mode' => $mode]) }}" wire:navigate class="w-8 h-8 flex items-center justify-center text-[#5c4033] hover:bg-gray-50 rounded-full transition">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="text-[#5c4033] font-bold text-lg">Hana's Bakery</h1>
        <button class="w-8 h-8 flex items-center justify-center text-[#5c4033] hover:bg-gray-50 rounded-full transition">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Active Mode Selector -->
    <div class="bg-white px-4 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-[#f4dfd4] flex items-center justify-center">
                @if($mode == 'pickup')
                    <img src="{{ asset('images/pickup.png') }}" class="w-10 h-10 object-contain drop-shadow-sm origin-bottom" alt="Pick Up">
                @else
                    <img src="{{ asset('images/delivery.png') }}" class="w-10 h-10 object-contain drop-shadow-sm origin-bottom" alt="Delivery">
                @endif
            </div>
            <h2 class="text-[#5c4033] font-bold text-xl">{{ $mode == 'pickup' ? 'Pick Up' : 'Delivery' }}</h2>
        </div>
        <button wire:click="$set('mode', '{{ $mode == 'pickup' ? 'delivery' : 'pickup' }}')" class="border border-[#5c4033] text-[#5c4033] rounded-full px-4 py-1.5 text-xs font-semibold hover:bg-gray-50 transition">
            Ubah ke {{ $mode == 'pickup' ? 'Delivery' : 'Pick Up' }}
        </button>
    </div>

    <div class="px-4 py-3 bg-white border-b border-gray-100">
        <span class="text-sm font-bold text-gray-500">{{ $stores->count() }} Store</span>
    </div>

    <!-- Store List -->
    <div class="flex flex-col gap-2 p-2">
        @forelse($stores as $index => $store)
            <div wire:click="selectStore({{ $store->id }})" class="bg-white p-5 rounded-xl border {{ session('selected_store_id') == $store->id ? 'border-blue-400 bg-blue-50/20' : 'border-gray-100' }} shadow-sm flex flex-col hover:shadow-md transition cursor-pointer">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-[#2d3748] font-bold text-lg leading-tight pr-4">
                        {{ $store->name }} {{ $index === 0 && $store->distance !== null ? '(store terdekat dari user)' : '' }}
                    </h3>
                    @if($index === 0)
                        <div class="w-5 h-5 rounded-full bg-[#5c4033] text-white flex items-center justify-center shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                    @endif
                </div>
                
                <p class="text-xs text-gray-500 mb-1">{{ $store->address ?? "Hana's Bakery (alamat lengkap store)" }}</p>
                
                <p class="text-xs font-bold text-gray-700 mb-3">
                    @if($store->distance !== null)
                        {{ $store->distance }} km dari lokasimu 
                        @if($index === 0)
                            • <span class="text-green-600">Terdekat</span>
                        @endif
                    @else
                        Menghitung jarak...
                    @endif
                </p>

                <div class="flex items-center gap-4 mb-4 border-b border-dashed border-gray-200 pb-4">
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-shopping-bag text-[#5c4033] text-sm"></i>
                        <span class="text-xs text-gray-500 font-medium">Pick Up</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-motorcycle text-blue-600 text-sm"></i>
                        <span class="text-xs text-gray-500 font-medium">Delivery</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div>
                        <span class="text-blue-600 font-bold">Buka</span>
                        <span class="text-gray-500 font-medium ml-2">{{ \Carbon\Carbon::parse($store->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($store->close_time)->format('H:i') }}</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-white rounded-xl">
                <i class="fas fa-store-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm font-medium">Belum ada store yang tersedia.</p>
            </div>
        @endforelse
    </div>

    <!-- Geolocation Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Cek apakah userLat dan userLng kosong, jika iya minta izin
            if (!@json($userLat)) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        // Kirim koordinat ke Livewire
                        @this.dispatch('location-updated', { lat: lat, lng: lng });
                    }, (error) => {
                        console.error("Error getting location: ", error);
                        // Fallback simulasi
                        @this.dispatch('location-updated', { lat: -6.402484, lng: 106.973495 });
                    }, {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 0
                    });
                }
            }
        });
    </script>
</div>

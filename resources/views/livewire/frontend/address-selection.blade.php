<div class="w-full min-h-screen bg-gray-50 pb-24 font-sans text-gray-800">
    <!-- Header -->
    <div class="bg-white px-4 py-4 flex items-center sticky top-0 z-20 shadow-sm md:px-12 lg:px-24">
        <a href="{{ route('ecommerce') }}" wire:navigate class="w-10 h-10 flex items-center justify-center text-[#5c4033] hover:bg-gray-100 rounded-full transition">
            <i class="fas fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-lg md:text-xl font-semibold text-[#5c4033] ml-2">Pilih Lokasi Pengiriman</h1>
    </div>

    <div class="max-w-3xl mx-auto px-4 md:px-12 lg:px-24 pt-4">
        <!-- Search Bar -->
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari alamat" 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#5c4033] focus:ring-1 focus:ring-[#5c4033] transition-colors">
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 mb-6">
            <button wire:click="$set('activeTab', 'terakhir')" 
                class="flex-1 py-2 text-sm font-medium rounded-xl border {{ $activeTab == 'terakhir' ? 'bg-[#eedcd3] border-[#5c4033] text-[#5c4033]' : 'bg-white border-gray-200 text-gray-400' }} transition-colors">
                Terakhir
            </button>
            <button wire:click="$set('activeTab', 'tersimpan')" 
                class="flex-1 py-2 text-sm font-medium rounded-xl border {{ $activeTab == 'tersimpan' ? 'bg-[#eedcd3] border-[#5c4033] text-[#5c4033]' : 'bg-white border-gray-200 text-gray-400' }} transition-colors">
                Tersimpan
            </button>
        </div>

        <!-- Address List -->
        <div class="bg-white border-t border-b border-gray-200 md:border md:rounded-2xl divide-y divide-gray-100 -mx-4 md:mx-0 px-4">
            @forelse($addresses as $address)
                <div class="py-5 flex items-start gap-4 cursor-pointer group" wire:click="selectAddress({{ $address->id }})">
                    <div class="mt-1">
                    <i class="fas fa-map-marker-alt text-gray-400 group-hover:text-[#5c4033] transition-colors text-lg"></i>
                </div>
                
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-sm md:text-base group-hover:text-[#5c4033] transition-colors">
                        {{ $address->title }} 
                        @if($address->is_primary)
                            <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded ml-2">Utama</span>
                        @endif
                    </h3>
                    
                    @if($address->detail_address)
                        <p class="text-xs md:text-sm text-gray-500 mt-1 leading-relaxed line-clamp-2">
                            {{ $address->detail_address }}
                        </p>
                    @endif
                </div>
                    
                    <div class="pl-2">
                        <i class="far fa-bookmark text-gray-400 text-lg group-hover:text-[#5c4033] transition-colors"></i>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-marked-alt text-gray-300 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada alamat tersimpan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bottom Sticky Button -->
    <div class="fixed bottom-[80px] left-0 right-0 p-4 bg-transparent z-20 md:bg-transparent md:border-none md:shadow-none md:static md:max-w-3xl md:mx-auto md:p-0 md:mt-8">
        <a href="{{ route('pelanggan.alamat.tambah') }}" wire:navigate class="w-full block text-center bg-[#5c4033] text-white font-bold py-3.5 rounded-2xl shadow-[0_8px_20px_-4px_rgba(92,64,51,0.5)] transition-all hover:bg-[#4a3328] hover:shadow-xl active:scale-[0.98]">
            Tambah Alamat
        </a>
    </div>
</div>

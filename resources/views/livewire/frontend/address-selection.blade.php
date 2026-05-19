<div class="w-full min-h-screen bg-gray-50 pb-24 font-sans text-gray-800">
    <div class="bg-white px-4 py-4 flex items-center sticky top-0 z-20 shadow-sm md:px-12 lg:px-24">
        <a href="{{ route('ecommerce') }}" wire:navigate class="w-10 h-10 flex items-center justify-center text-[#5c4033] hover:bg-gray-100 rounded-full transition">
            <i class="fas fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-lg md:text-xl font-semibold text-[#5c4033] ml-2">Pilih Lokasi Pengiriman</h1>
    </div>

    <div class="max-w-3xl mx-auto px-4 md:px-12 lg:px-24 pt-4">
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari alamat" 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#5c4033] focus:ring-1 focus:ring-[#5c4033] transition-colors">
        </div>

        <div class="flex gap-2 mb-6">
            <button wire:click="$set('activeTab', 'terakhir')" 
                class="flex-1 py-2 text-sm font-medium rounded-xl border {{ $activeTab == 'terakhir' ? 'bg-[#eedcd3] border-[#5c4033] text-[#5c4033]' : 'bg-white border-gray-200 text-gray-400 hover:bg-gray-50' }} transition-colors">
                Terakhir
            </button>
            <button wire:click="$set('activeTab', 'tersimpan')" 
                class="flex-1 py-2 text-sm font-medium rounded-xl border {{ $activeTab == 'tersimpan' ? 'bg-[#eedcd3] border-[#5c4033] text-[#5c4033]' : 'bg-white border-gray-200 text-gray-400 hover:bg-gray-50' }} transition-colors">
                Tersimpan
            </button>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($addresses as $address)
                <div class="bg-white p-4 rounded-xl border-2 {{ session('selected_address_id') == $address->id ? 'border-[#5c4033] bg-[#f4dfd4]/10' : 'border-gray-100 hover:border-gray-200' }} shadow-sm transition-colors">
                    
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base">{{ $address->title }}</h3>
                            @if($address->is_primary)
                                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wide">Utama</span>
                            @endif
                        </div>
                        <i class="{{ session('selected_address_id') == $address->id ? 'fas fa-map-marker-alt text-[#5c4033]' : 'far fa-bookmark text-gray-300' }} text-lg"></i>
                    </div>

                    <p class="text-xs md:text-sm font-bold text-gray-700 mb-1">
                        {{ $address->receiver_name ?? 'Tanpa Nama' }} 
                        <span class="font-normal text-gray-400 mx-1">|</span> 
                        {{ $address->receiver_phone ?? '-' }}
                    </p>
                    
                    <p class="text-xs md:text-sm text-gray-500 leading-relaxed mb-4 line-clamp-2">
                        {{ $address->detail_address ?? 'Tidak ada detail alamat tambahan.' }}
                    </p>

                    <div class="border-t border-dashed border-gray-200 pt-3 flex items-center justify-between">
                        
                        <a href="{{ route('pelanggan.alamat.edit', $address->id) }}" wire:navigate class="text-sm font-bold text-[#5c4033] hover:text-[#8b6f5e] transition-colors flex items-center gap-1.5 p-1 -ml-1">
                            <i class="fas fa-edit text-xs"></i> Ubah
                        </a>

                        @if(session('selected_address_id') == $address->id)
                            <div class="flex items-center gap-1.5 text-[#5c4033] text-sm font-bold px-2 py-1">
                                <i class="fas fa-check-circle"></i> Terpilih
                            </div>
                        @else
                            <button wire:click="selectAddress({{ $address->id }})" class="border border-[#5c4033] text-[#5c4033] hover:bg-[#5c4033] hover:text-white px-5 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                Pilih
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="py-12 text-center bg-white rounded-2xl border border-gray-100">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-marked-alt text-gray-300 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada alamat tersimpan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="fixed bottom-[20px] md:bottom-[40px] left-0 right-0 px-4 z-20 md:max-w-3xl md:mx-auto">
        <a href="{{ route('pelanggan.alamat.tambah') }}" wire:navigate class="w-full flex items-center justify-center gap-2 bg-[#5c4033] text-white font-bold py-3.5 rounded-2xl shadow-[0_8px_20px_-4px_rgba(92,64,51,0.5)] transition-all hover:bg-[#4a3328] hover:shadow-xl active:scale-[0.98]">
            <i class="fas fa-plus"></i> Tambah Alamat Baru
        </a>
    </div>
</div>
<div class="w-full min-h-screen bg-[#f3f4f6] pb-24 font-sans text-gray-800 relative">
    <div class="bg-white">
        <div class="px-4 py-4 flex items-center sticky top-0 z-20 md:max-w-3xl md:mx-auto">
            <a href="{{ route('pelanggan.alamat') }}" wire:navigate class="text-gray-500 hover:text-[#5c4033] transition">
                <i class="fas fa-chevron-left text-lg"></i>
            </a>
            <h1 class="text-lg font-medium text-[#5c4033] ml-4 flex-1 text-center pr-6">Ubah Alamat</h1>
        </div>
    </div>

    <form wire:submit.prevent="updateAddress" class="md:max-w-3xl md:mx-auto md:pb-6">
        
        <div class="bg-white mt-2 px-4 py-5 md:rounded-xl md:shadow-sm md:mt-4">
            <h2 class="text-base font-bold text-[#1f2937] mb-4">Detail Alamat</h2>
            
            <div class="mb-5" x-data="gpsManager()">
                <label class="block text-xs text-gray-500 mb-1.5">Titik Koordinat GPS <span class="text-red-500">*</span></label>
                
                <div class="p-3 rounded-lg border {{ $latitude && $longitude ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} flex items-center gap-3 transition-colors mb-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $latitude && $longitude ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div class="flex-1">
                        @if($latitude && $longitude)
                            <p class="text-xs text-green-700 font-bold"><i class="fas fa-check-circle mr-1"></i> Titik Lokasi Tersimpan</p>
                            <p class="text-[10px] text-green-600 mt-0.5">{{ $latitude }}, {{ $longitude }}</p>
                        @else
                            <p class="text-xs text-red-700 font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Titik Lokasi Kosong</p>
                            <p class="text-[10px] text-red-600 mt-0.5">Wajib diperbarui untuk pengiriman</p>
                        @endif
                    </div>
                </div>

                <button type="button" @click="getNewLocation()" :disabled="isLoading" class="w-full py-2 rounded-lg border-2 border-[#5c4033] text-[#5c4033] text-xs font-bold flex justify-center items-center gap-2 hover:bg-[#5c4033] hover:text-white transition disabled:opacity-50">
                    <span x-show="!isLoading"><i class="fas fa-location-arrow"></i> Perbarui Titik Saat Ini</span>
                    <span x-show="isLoading"><i class="fas fa-spinner fa-spin"></i> Mendapatkan lokasi...</span>
                </button>
                @error('latitude') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Alamat <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033]">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Detail Alamat (opsional)</label>
                <input type="text" wire:model="detail_address" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033]">
            </div>
            
            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" wire:model="is_primary" id="is_primary" class="rounded border-gray-300 text-[#5c4033] focus:ring-[#5c4033]">
                <label for="is_primary" class="text-sm text-gray-600">Jadikan sebagai alamat utama</label>
            </div>
        </div>

        <div class="bg-white mt-2 px-4 py-5 md:rounded-xl md:shadow-sm md:mt-4">
            <h2 class="text-base font-bold text-[#1f2937] mb-4">Detail Penerima</h2>
            
            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                <input type="text" wire:model="receiver_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] uppercase">
                @error('receiver_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="tel" wire:model="receiver_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033]">
                @error('receiver_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white md:bg-transparent md:static md:mt-6 z-20 border-t border-gray-100 md:border-0">
            <button type="submit" wire:loading.attr="disabled" class="w-full bg-[#5c4033] text-white font-medium text-sm py-3.5 rounded-xl shadow-sm transition hover:bg-[#4a3328] active:scale-[0.98] disabled:opacity-70 flex justify-center items-center gap-2">
                <span wire:loading.remove wire:target="updateAddress">Simpan Perubahan</span>
                <span wire:loading wire:target="updateAddress"><i class="fas fa-circle-notch fa-spin"></i> Menyimpan...</span>
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gpsManager', () => ({
                isLoading: false,
                getNewLocation() {
                    this.isLoading = true;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                @this.dispatch('update-coordinates', { lat: position.coords.latitude, lng: position.coords.longitude });
                                this.isLoading = false;
                            },
                            (error) => {
                                console.error("Error GPS:", error);
                                alert("Gagal mendapatkan lokasi. Pastikan GPS aktif.");
                                this.isLoading = false;
                            },
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    } else {
                        alert("Browser tidak mendukung GPS.");
                        this.isLoading = false;
                    }
                }
            }))
        })
    </script>
</div>
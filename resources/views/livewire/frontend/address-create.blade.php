<div class="w-full min-h-screen bg-[#f3f4f6] pb-24 font-sans text-gray-800" x-data="addressForm()">
    <!-- Header -->
    <div class="bg-white">
        <div class="px-4 py-4 flex items-center sticky top-0 z-20 md:max-w-3xl md:mx-auto">
            <a href="{{ route('pelanggan.alamat') }}" wire:navigate class="text-gray-500 hover:text-[#5c4033] transition">
                <i class="fas fa-chevron-left text-lg"></i>
            </a>
            <h1 class="text-lg font-medium text-[#5c4033] ml-4 flex-1 text-center pr-6">Tambah Alamat</h1>
        </div>
        
        <div class="px-4 pb-4 md:max-w-3xl md:mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <!-- GPS Trigger disguised as search -->
                <button type="button" @click="requestLocation()" :disabled="isLoading" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-left text-gray-500 bg-white focus:outline-none flex items-center justify-between">
                    <span x-text="isLoading ? 'Mencari Lokasi...' : (isLocationSet ? 'Titik GPS Terkunci (Ketuk untuk perbarui)' : 'Cari Lokasi / Ambil GPS Saat Ini')"></span>
                    <i class="fas fa-crosshairs text-blue-500" x-show="!isLoading && !isLocationSet"></i>
                    <i class="fas fa-check-circle text-green-500" x-show="isLocationSet && !isLoading"></i>
                    <i class="fas fa-spinner fa-spin text-blue-500" x-show="isLoading" style="display: none;"></i>
                </button>
                @error('latitude') <span class="text-red-500 text-xs mt-1 block px-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <form wire:submit.prevent="saveAddress" class="md:max-w-3xl md:mx-auto md:pb-6">
        
        <!-- Detail Alamat Section -->
        <div class="bg-white mt-2 px-4 py-5 md:rounded-xl md:shadow-sm md:mt-4">
            <h2 class="text-base font-bold text-[#1f2937] mb-4">Detail Alamat</h2>
            
            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Alamat</label>
                <input type="text" wire:model="title" placeholder="Contoh: Rumah, Kantor" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Detail Alamat (opsional)</label>
                <input type="text" wire:model="detail_address" placeholder="Contoh: Tower A, Kamar Nomo 22" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('detail_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <!-- Checkbox Set Primary -->
            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" wire:model="is_primary" id="is_primary" class="rounded border-gray-300 text-[#5c4033] focus:ring-[#5c4033]">
                <label for="is_primary" class="text-sm text-gray-600">Jadikan sebagai alamat utama</label>
            </div>
        </div>

        <!-- Detail Penerima Section -->
        <div class="bg-white mt-2 px-4 py-5 md:rounded-xl md:shadow-sm md:mt-4">
            <h2 class="text-base font-bold text-[#1f2937] mb-4">Detail Penerima</h2>
            
            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Penerima</label>
                <input type="text" wire:model="receiver_name" placeholder="RINA" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300 uppercase">
                @error('receiver_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Nomor Telepon</label>
                <input type="tel" wire:model="receiver_phone" placeholder="+6280000000000" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('receiver_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Bottom Sticky Button -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white md:bg-transparent md:static md:mt-6">
            <button type="submit" class="w-full bg-[#5c4033] text-white font-medium text-sm py-3.5 rounded-xl shadow-sm transition hover:bg-[#4a3328] active:scale-[0.98]">
                Simpan
            </button>
        </div>
    </form>

    <!-- Alpine.js logic for GPS -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addressForm', () => ({
                isLoading: false,
                isLocationSet: @entangle('latitude').live ? true : false,
                
                requestLocation() {
                    this.isLoading = true;
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                
                                // Call Livewire component method
                                @this.setLocation(lat, lng);
                                
                                this.isLocationSet = true;
                                this.isLoading = false;
                            },
                            (error) => {
                                this.isLoading = false;
                                let msg = 'Gagal mengambil lokasi.';
                                if (error.code === error.PERMISSION_DENIED) {
                                    msg = 'Mohon izinkan akses lokasi (GPS) pada browser Anda untuk melanjutkan.';
                                }
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: msg,
                                    confirmButtonColor: '#5c4033'
                                });
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        this.isLoading = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Didukung',
                            text: 'Browser Anda tidak mendukung Geolocation.',
                            confirmButtonColor: '#5c4033'
                        });
                    }
                }
            }));
        });
    </script>
</div>

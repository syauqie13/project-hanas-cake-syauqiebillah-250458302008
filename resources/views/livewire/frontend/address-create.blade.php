<div class="w-full min-h-screen bg-[#f3f4f6] pb-24 font-sans text-gray-800 relative">
    <div class="bg-white">
        <div class="px-4 py-4 flex items-center sticky top-0 z-20 md:max-w-3xl md:mx-auto">
            <a href="{{ route('pelanggan.alamat') }}" wire:navigate class="text-gray-500 hover:text-[#5c4033] transition">
                <i class="fas fa-chevron-left text-lg"></i>
            </a>
            <h1 class="text-lg font-medium text-[#5c4033] ml-4 flex-1 text-center pr-6">Tambah Alamat</h1>
        </div>
        
        <div class="px-4 pb-4 md:max-w-3xl md:mx-auto">
            <p class="text-sm text-gray-600">Mohon izinkan akses lokasi pada browser Anda. Sistem membutuhkan titik koordinat untuk menghitung jarak dan biaya pengiriman.</p>
        </div>
    </div>

    <form wire:submit.prevent="saveAddress" class="md:max-w-3xl md:mx-auto md:pb-6">
        
        <div class="bg-white mt-2 px-4 py-5 md:rounded-xl md:shadow-sm md:mt-4">
            <h2 class="text-base font-bold text-[#1f2937] mb-4">Detail Alamat</h2>
            
            <div class="mb-5">
                <label class="block text-xs text-gray-500 mb-1.5">Titik Koordinat GPS <span class="text-red-500">*</span></label>
                <div class="p-3 rounded-lg border {{ $latitude && $longitude ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} flex items-center gap-3 transition-colors">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $latitude && $longitude ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-500' }}">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div>
                        @if($latitude && $longitude)
                            <p class="text-xs text-green-700 font-bold"><i class="fas fa-check-circle mr-1"></i> Lokasi berhasil didapatkan</p>
                            <p class="text-[10px] text-green-600 mt-0.5">Siap untuk pengiriman</p>
                        @else
                            <p class="text-xs text-gray-600 font-bold"><i class="fas fa-spinner fa-spin mr-1"></i> Mencari lokasi...</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Pastikan GPS aktif & browser diizinkan</p>
                        @endif
                    </div>
                </div>
                @error('latitude') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Alamat <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title" placeholder="Contoh: Rumah, Kantor" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Detail Alamat (opsional)</label>
                <input type="text" wire:model="detail_address" placeholder="Contoh: Depan masjid, pagar hitam" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('detail_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                <input type="text" wire:model="receiver_name" placeholder="RINA" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300 uppercase">
                @error('receiver_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-xs text-gray-500 mb-1.5">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="tel" wire:model="receiver_phone" placeholder="081234567890" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#5c4033] transition-colors placeholder-gray-300">
                @error('receiver_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white md:bg-transparent md:static md:mt-6 z-20 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] md:shadow-none border-t border-gray-100 md:border-0">
            <button type="submit" wire:loading.attr="disabled" class="w-full bg-[#5c4033] text-white font-medium text-sm py-3.5 rounded-xl shadow-sm transition hover:bg-[#4a3328] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                <span wire:loading.remove wire:target="saveAddress">Simpan Alamat</span>
                <span wire:loading wire:target="saveAddress">
                    <i class="fas fa-circle-notch fa-spin"></i> Menyimpan...
                </span>
            </button>
        </div>
    </form>

    @script
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Mengirim koordinat ke PHP (wajib sesuai dengan #[On('update-coordinates')] di AddressCreate.php)
                    $wire.dispatch('update-coordinates', { lat: lat, lng: lng });
                }, 
                (error) => {
                    console.error("Gagal mendapatkan lokasi GPS: ", error);
                    // Gunakan SweetAlert jika Anda sudah menginstallnya, atau fallback ke alert bawaan
                    alert("Akses lokasi ditolak atau gagal. Mohon aktifkan GPS perangkat dan izinkan browser mengakses lokasi untuk menghitung ongkir.");
                }, 
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            alert("Browser/Perangkat Anda tidak mendukung fitur lokasi GPS.");
        }
    </script>
    @endscript
</div>
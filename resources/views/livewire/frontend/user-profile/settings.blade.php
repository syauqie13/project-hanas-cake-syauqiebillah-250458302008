<div class="w-full min-h-screen bg-[#fcfcfc] pb-32 font-sans text-gray-800 antialiased max-w-md mx-auto shadow-sm md:shadow-xl md:rounded-2xl md:mt-10 md:border md:border-gray-100 relative">
    
    @if($step === 'main')
        <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100">
            <a href="{{ route('pelanggan.profile') }}" wire:navigate class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </a>
            <h1 class="text-lg font-medium text-[#4a3328]">Pengaturan</h1>
            <div class="w-5"></div> </div>

        <div class="px-2 mt-4">
            <button wire:click="setStep('pin')" class="w-full flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition">
                <span class="font-medium text-gray-800 text-sm">Atur PIN Hana's Bakery</span>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            
            <button wire:click="setStep('notif')" class="w-full flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition">
                <span class="font-medium text-gray-800 text-sm">Atur Notifikasi</span>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>

            <button wire:click="setStep('bahasa')" class="w-full flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition">
                <span class="font-medium text-gray-800 text-sm">Ganti Bahasa</span>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
        </div>
    @endif


    @if($step === 'pin')
        <div class="px-6 py-6 flex items-center justify-between">
            <button wire:click="setStep('main')" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-lg font-medium text-[#4a3328]">{{ $has_pin ? 'Ubah PIN' : 'Buat PIN Baru' }}</h1>
            <div class="w-5"></div>
        </div>

        <div class="px-6 mt-8 space-y-8">
            <p class="text-xs text-gray-500 mb-6">Gunakan PIN 6 angka untuk keamanan setiap transaksi pembayaran Anda.</p>

            @if($has_pin)
            <div class="relative border-b border-gray-400 pb-2">
                <label class="block text-[11px] text-gray-500 mb-1">PIN Lama</label>
                <input type="password" inputmode="numeric" wire:model="old_pin" class="w-full bg-transparent outline-none text-gray-800 text-lg font-medium tracking-widest" placeholder="••••••" maxlength="6">
                @error('old_pin') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="relative border-b border-gray-400 pb-2">
                <label class="block text-[11px] text-gray-500 mb-1">PIN Baru</label>
                <input type="password" inputmode="numeric" wire:model="new_pin" class="w-full bg-transparent outline-none text-gray-800 text-lg font-medium tracking-widest" placeholder="••••••" maxlength="6">
                @error('new_pin') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>

            <div class="relative border-b border-gray-400 pb-2">
                <label class="block text-[11px] text-gray-500 mb-1">Konfirmasi PIN Baru</label>
                <input type="password" inputmode="numeric" wire:model="new_pin_confirmation" class="w-full bg-transparent outline-none text-gray-800 text-lg font-medium tracking-widest" placeholder="••••••" maxlength="6">
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-0">
            <button wire:click="updatePin" class="w-full bg-[#1c6b38] text-white font-medium py-4 text-[15px] hover:bg-[#15532b] transition">
                Simpan PIN
            </button>
        </div>
    @endif


    @if($step === 'notif')
        <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100">
            <button wire:click="setStep('main')" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-lg font-medium text-[#4a3328]">Atur Notifikasi</h1>
            <div class="w-5"></div>
        </div>

        <div class="px-6 mt-6 space-y-6">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <span class="block text-sm font-medium text-gray-800">Notifikasi Email</span>
                    <span class="block text-[11px] text-gray-500 mt-1">Terima struk belanja dan promo via Email</span>
                </div>
                <div class="relative">
                    <input type="checkbox" wire:model="notif_email" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1c6b38]"></div>
                </div>
            </label>

            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <span class="block text-sm font-medium text-gray-800">Notifikasi WhatsApp</span>
                    <span class="block text-[11px] text-gray-500 mt-1">Terima status pesanan & pengiriman via WA</span>
                </div>
                <div class="relative">
                    <input type="checkbox" wire:model="notif_wa" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1c6b38]"></div>
                </div>
            </label>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-0">
            <button wire:click="updateNotif" class="w-full bg-[#1c6b38] text-white font-medium py-4 text-[15px] hover:bg-[#15532b] transition">
                Simpan
            </button>
        </div>
    @endif


    @if($step === 'bahasa')
        <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100">
            <button wire:click="setStep('main')" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-lg font-medium text-[#4a3328]">Ganti Bahasa</h1>
            <div class="w-5"></div>
        </div>

        <div class="px-6 mt-6 space-y-3">
            <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                <div class="relative flex items-center justify-center w-5 h-5">
                    <input type="radio" wire:model="locale" value="id" class="peer sr-only">
                    <div class="w-5 h-5 border-2 border-gray-400 rounded-full peer-checked:border-[#1c6b38] transition-colors"></div>
                    <div class="absolute w-2.5 h-2.5 bg-[#1c6b38] rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-800">Bahasa Indonesia</span>
                    <span class="text-[11px] text-gray-500">Terapkan bahasa Indonesia (ID)</span>
                </div>
            </label>

            <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                <div class="relative flex items-center justify-center w-5 h-5">
                    <input type="radio" wire:model="locale" value="en" class="peer sr-only">
                    <div class="w-5 h-5 border-2 border-gray-400 rounded-full peer-checked:border-[#1c6b38] transition-colors"></div>
                    <div class="absolute w-2.5 h-2.5 bg-[#1c6b38] rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-800">English</span>
                    <span class="text-[11px] text-gray-500">Apply English language (EN)</span>
                </div>
            </label>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-0">
            <button wire:click="updateLanguage" class="w-full bg-[#1c6b38] text-white font-medium py-4 text-[15px] hover:bg-[#15532b] transition">
                Simpan
            </button>
        </div>
    @endif

</div>
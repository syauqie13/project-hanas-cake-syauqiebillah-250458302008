<div x-data="{ showLogoutModal: false }" class="w-full min-h-screen bg-gray-50 md:bg-[#f4f6f9] pb-28 md:pb-16 font-sans text-gray-800 antialiased relative">
    
    <div class="max-w-md md:max-w-4xl mx-auto px-4 pt-6 md:pt-12">
        
        <div class="flex flex-col md:flex-row gap-5 md:gap-8 items-start">
            
            <div class="w-full md:w-1/3 flex flex-col gap-5 md:sticky md:top-10">
                
                <a href="{{ route('pelanggan.profile.edit') }}" wire:navigate class="block bg-[#5c4033] rounded-2xl p-4 md:p-8 flex items-center md:flex-col md:justify-center md:text-center justify-between shadow-sm md:shadow-lg hover:shadow-md transition group relative overflow-hidden">
                    <div class="hidden md:block absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    
                    <div class="flex items-center md:flex-col gap-4 w-full relative z-10">
                        <div class="w-14 h-14 md:w-28 md:h-28 rounded-full overflow-hidden border-2 border-white/20 shrink-0 bg-white/10 md:mb-3 shadow-inner">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=eedcd3&color=5c4033" alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        
                        <div class="flex-1 md:w-full">
                            <h2 class="text-white font-bold text-lg md:text-xl md:tracking-wide leading-tight uppercase">{{ $user->name }}</h2>
                            <p class="text-white/80 text-sm md:text-sm mt-0.5">{{ $user->phone ?? 'Belum ada nomor HP' }}</p>
                        </div>
                        
                        <i class="fas fa-chevron-right text-white text-lg md:hidden"></i>
                    </div>
                    
                    <div class="hidden md:block mt-6 w-full bg-white/10 group-hover:bg-white/20 text-white text-sm py-3 rounded-xl font-medium transition cursor-pointer">
                        <i class="fas fa-pencil-alt mr-2 text-xs"></i> {{ __('Edit Profil') }}
                    </div>
                </a>

                <div class="hidden md:block bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                    <h3 class="font-bold text-gray-800 text-sm mb-4">Perlu Bantuan?</h3>
                    <a href="https://wa.me/62859737389395" target="_blank" class="inline-flex flex-col items-center justify-center p-4 border border-green-100 bg-green-50/50 rounded-xl hover:bg-green-50 transition w-full">
                        <i class="fab fa-whatsapp text-green-500 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Customer Service</p>
                        <p class="text-green-600 font-bold text-base tracking-wide mt-1">0859-7373-89395</p>
                    </a>
                </div>

            </div>

            <div class="w-full md:w-2/3 flex flex-col gap-5">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <h3 class="hidden md:block text-[11px] font-bold text-gray-400 uppercase tracking-widest px-6 pt-6 pb-2">{{ __('Pengaturan Akun') }}</h3>
                    <a href="{{ route('pelanggan.alamat') }}" wire:navigate class="flex items-center justify-between p-4 md:px-6 md:py-5 border-b border-gray-100 hover:bg-gray-50 transition group">
                        <span class="font-medium text-gray-700 md:group-hover:text-[#5c4033] text-sm md:text-base">{{ __('Alamat Tersimpan') }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm md:group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('pelanggan.my-orders') }}" class="flex items-center justify-between p-4 md:px-6 md:py-5 border-b border-gray-100 hover:bg-gray-50 transition group">
                        <span class="font-medium text-gray-700 md:group-hover:text-[#5c4033] text-sm md:text-base">{{ __('Pesanan Saya') }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm md:group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('pelanggan.profile.settings') }}" class="flex items-center justify-between p-4 md:px-6 md:py-5 hover:bg-gray-50 transition group">
                        <span class="font-medium text-gray-700 md:group-hover:text-[#5c4033] text-sm md:text-base">{{ __('Pengaturan') }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm md:group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <h3 class="hidden md:block text-[11px] font-bold text-gray-400 uppercase tracking-widest px-6 pt-6 pb-2">{{ __('Informasi Hukum') }}</h3>
                    <a href="{{ route('pelanggan.terms') }}" wire:navigate class="flex items-center justify-between p-4 md:px-6 md:py-5 border-b border-gray-100 hover:bg-gray-50 transition group">
                        <span class="font-medium text-gray-700 md:group-hover:text-[#5c4033] text-sm md:text-base">{{ __('Syarat dan Ketentuan') }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm md:group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('pelanggan.privacy') }}" wire:navigate class="flex items-center justify-between p-4 md:px-6 md:py-5 hover:bg-gray-50 transition group">
                        <span class="font-medium text-gray-700 md:group-hover:text-[#5c4033] text-sm md:text-base">{{ __('Kebijakan Privasi') }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm md:group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="md:hidden bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Perlu Bantuan?</h3>
                    <a href="https://wa.me/62859737389395" target="_blank" class="flex items-center justify-between p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-green-500 text-[28px]"></i>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium">Hana's Cake Customer Service</p>
                                <p class="text-green-500 font-bold text-sm tracking-wide">0859-7373-89395</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    </a>
                </div>

                <div class="pt-2 md:pt-4 md:mt-4 md:flex md:justify-end">
                    <button type="button" @click="showLogoutModal = true" class="w-full md:w-auto md:px-12 bg-[#ffebee] md:bg-white text-red-500 md:border md:border-red-200 font-bold text-sm py-4 md:py-3.5 rounded-xl shadow-sm hover:bg-red-500 md:hover:bg-red-50 hover:text-white md:hover:text-red-600 transition-colors active:scale-[0.98]">
                        {{ __('Logout') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showLogoutModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm" 
             @click="showLogoutModal = false">
        </div>
        
        <div x-show="showLogoutModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-3xl p-6 w-full max-w-sm text-center shadow-2xl z-10">
            
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                <i class="fas fa-sign-out-alt text-red-500 text-2xl"></i>
            </div>
            
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ __('Yakin ingin keluar?') }}</h3>
            <p class="text-xs text-gray-500 mb-6 px-4">{{ __('Kamu harus login kembali untuk melihat pesanan dan profilmu.') }}</p>
            
            <div class="flex gap-3">
                <button type="button" @click="showLogoutModal = false" class="flex-1 bg-gray-100 text-gray-600 font-bold text-sm py-3 rounded-xl hover:bg-gray-200 transition-colors">
                    {{ __('Batal') }}
                </button>
                <button type="button" wire:click="logout" class="flex-1 bg-red-500 text-white font-bold text-sm py-3 rounded-xl shadow-md hover:bg-red-600 transition-colors">
                    {{ __('Ya, Keluar') }}
                </button>
            </div>
        </div>
    </div>
</div>
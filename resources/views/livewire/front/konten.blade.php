<div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-24 md:pb-12 md:max-w-5xl md:mt-10 md:rounded-[2rem] md:shadow-2xl overflow-hidden md:border md:border-gray-100">
    
    <!-- Header: Lebih lega di desktop -->
    <div class="px-6 pt-8 pb-5 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#5c4033] tracking-tight">
            Hi {{ Auth::check() ? explode(' ', Auth::user()->name)[0] : 'Guest' }}, <span class="block md:inline font-medium text-lg md:text-3xl text-[#8b6f5e]">Selamat Datang!</span>
        </h1>
        <div x-data @click="$dispatch('open-inbox')" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#5c4033] shadow-sm border border-gray-100 cursor-pointer hover:bg-gray-50 transition-all">
            <i class="fas fa-bell text-xl"></i>
        </div>
    </div>

    <!-- Promo Banner: Tinggi adaptif -->
    <div class="px-6 mb-8">
        <div class="relative w-full h-48 md:h-72 rounded-[1.5rem] overflow-hidden shadow-lg bg-[#5c4033]">
            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=1000&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-50 mix-blend-overlay">
            
            <div class="relative z-10 p-6 md:p-12 flex flex-col h-full justify-center max-w-lg">
                <span class="text-white/90 text-xs md:text-base mb-2 font-semibold tracking-widest uppercase">Promo Hari Ini</span>
                <h2 class="text-white text-2xl md:text-5xl font-bold leading-tight mb-3">Diskon 20%<br>Semua Pastri</h2>
                <p class="text-white/80 text-[10px] md:text-sm mb-6 flex items-center gap-2">
                    <i class="far fa-clock"></i> Berlaku Sampai Pukul 17.00 WIB
                </p>
                <a href="{{ route('ecommerce', ['mode' => 'pickup']) }}" wire:navigate class="bg-white text-[#5c4033] text-xs md:text-base font-bold py-3 px-8 rounded-xl w-max hover:bg-gray-100 transition-all transform hover:scale-105 shadow-xl">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Menu Pemesanan: Grid yang berubah dari 1 ke 2 kolom di layar besar -->
    <div class="px-6 mb-8">
        <h2 class="text-xl md:text-2xl font-bold text-[#5c4033] mb-5">Pesan Sekarang?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <!-- Pick Up -->
            <a href="{{ route('ecommerce', ['mode' => 'pickup']) }}" wire:navigate class="relative overflow-hidden bg-[#7c5b4e] rounded-2xl p-6 h-44 md:h-56 flex items-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="relative z-10">
                    <h3 class="text-white font-bold text-2xl md:text-3xl mb-2">Pick Up</h3>
                    <p class="text-white/90 text-sm md:text-base leading-relaxed">Ambil di Store<br>tanpa antri</p>
                </div>
                <!-- Gambar di kanan untuk keseimbangan -->
                <div class="absolute -right-4 -bottom-4 w-32 md:w-44 h-32 md:h-44 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-500">
                    <img src="{{ asset('images/pickup.png') }}" class="w-full h-full object-contain drop-shadow-2xl">
                </div>
            </a>

            <!-- Delivery -->
            <a href="{{ route('ecommerce', ['mode' => 'delivery']) }}" wire:navigate class="relative overflow-hidden bg-[#f4dfd4] rounded-2xl p-6 h-44 md:h-56 flex items-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="relative z-10">
                    <h3 class="text-[#5c4033] font-bold text-2xl md:text-3xl mb-2">Delivery</h3>
                    <p class="text-[#8b6f5e] text-sm md:text-base leading-relaxed">Garansi tepat<br>waktu, dijamin!</p>
                </div>
                <div class="absolute -right-4 -bottom-4 w-32 md:w-44 h-32 md:h-44 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
                    <img src="{{ asset('images/delivery.png') }}" class="w-full h-full object-contain drop-shadow-2xl">
                </div>
            </a>
        </div>

        <!-- Pre-Order: Full width dengan layout flex-row -->
        <a href="{{ route('ecommerce', ['mode' => 'po']) }}" wire:navigate class="relative overflow-hidden bg-white border-2 border-[#f4dfd4] rounded-2xl p-6 md:p-8 flex justify-between items-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex-1">
                <h3 class="text-[#5c4033] font-bold text-2xl md:text-3xl mb-2">Pre-Order</h3>
                <p class="text-[#8b6f5e] text-sm md:text-lg">Rencanakan momen spesial<br class="hidden md:block"> bersama Hana's Bakery</p>
            </div>
            <div class="w-24 h-24 md:w-36 md:h-36 group-hover:scale-110 transition-transform duration-500">
                <img src="{{ asset('images/preorder.png') }}" class="w-full h-full object-contain drop-shadow-xl">
            </div>
        </a>
    </div>

    <!-- Bantuan: Responsif Stack -->
    <div class="px-6 mb-10">
        <h2 class="text-xl md:text-2xl font-bold text-[#5c4033] mb-5">Perlu Bantuan?</h2>
        <a href="https://wa.me/6288225853364" target="_blank" class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center gap-5 hover:shadow-md transition group">
            <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-500 transition-colors duration-300">
                <i class="fab fa-whatsapp text-3xl text-green-500 group-hover:text-white"></i>
            </div>
            <div>
                <p class="text-xs md:text-sm text-gray-500 font-medium mb-1">Customer Service (Chat Only)</p>
                <p class="text-[#5c4033] font-bold text-lg md:text-xl">081-2222-3333</p>
            </div>
            <div class="md:ml-auto">
                <span class="text-green-600 text-sm font-bold bg-green-50 px-4 py-2 rounded-full">Online</span>
            </div>
        </a>
    </div>

    <!-- Kotak Masuk Drawer (AlpineJS) -->
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-inbox.window="open = true"
         style="display: none;" 
         class="fixed inset-0 z-[60] flex flex-col justify-end sm:items-center sm:justify-center">
         
        <!-- Backdrop -->
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-black/40 backdrop-blur-sm" 
             @click="open = false" aria-hidden="true"></div>

        <!-- Drawer Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
             class="relative w-full sm:max-w-md h-[90vh] sm:h-[80vh] bg-[#f4f5f6] rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col pb-safe">
            
            <!-- Handle bar for mobile -->
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mt-4 shrink-0 sm:hidden"></div>
            
            <!-- Header -->
            <div class="px-6 py-4 flex items-center justify-between shrink-0">
                <div class="w-6"></div> <!-- Spacer for centering -->
                <h3 class="text-xl font-bold text-center text-[#5c4033]">Kotak Masuk</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto px-4 pb-6">
                @php
                    $notifications = Auth::check() ? Auth::user()->notifications : collect();
                @endphp

                @if($notifications->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-center pb-20">
                        <p class="text-[#2d3748] font-semibold text-sm">No message(s) to show</p>
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($notifications as $notification)
                        <div class="bg-[#eedcd3] rounded-2xl p-4 border border-[#e2cdc2] shadow-sm flex items-start gap-3">
                            <div class="flex-1">
                                <h4 class="font-bold text-[#2d3748] text-sm mb-1">{{ $notification->data['title'] ?? 'Notifikasi' }}</h4>
                                <p class="text-xs text-[#5c4033]/80 leading-relaxed mb-3">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <div class="flex items-center gap-3 text-[10px] text-gray-500 font-medium">
                                    <span>{{ $notification->created_at->format('d-m-Y H:i') }}</span>
                                    <span>Riwayat Status</span>
                                </div>
                            </div>
                            @if(isset($notification->data['image']))
                                <div class="w-16 h-16 bg-[#e2cdc2] rounded-xl overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $notification->data['image']) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
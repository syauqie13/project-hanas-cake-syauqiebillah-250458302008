<div class="w-full min-h-screen bg-gray-50 pb-20 md:pb-0 overflow-x-hidden">
    <!-- Dynamic Header Area -->
    @php
        $bgClass = 'bg-gradient-to-r from-[#5c4033] to-[#8b6f5e]';
        $title = 'Pre-Order';
        $subtitle = 'Rencanakan momen spesial';
        $imageSrc = asset('images/preorder.png');
        $imageClass = 'w-full h-full';

        if ($mode == 'pickup') {
            $bgClass = 'bg-gradient-to-r from-[#7c5b4e] to-[#8b6f5e]';
            $title = 'Pick Up';
            $subtitle = 'Ambil di Store tanpa antri';
            $imageSrc = asset('images/pickup.png');
            $imageClass = 'w-full h-full';
        } elseif ($mode == 'delivery') {
            $bgClass = 'bg-[#f4dfd4]';
            $title = 'Delivery';
            $subtitle = 'Garansi tepat waktu, dijamin!';
            $imageSrc = asset('images/delivery.png');
            $imageClass = 'w-full h-full';
        }
    @endphp

    <div
        class="{{ $bgClass }} relative px-4 md:px-12 lg:px-24 pt-6 pb-5 md:pt-8 md:pb-6 rounded-b-[2rem] shadow-sm">
        <div class="max-w-7xl mx-auto relative">
            <a href="{{ route('front') }}" wire:navigate
                class="absolute top-4 left-4 w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/30 transition">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 md:w-24 md:h-24 bg-white/50 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="{{ $imageSrc }}" class="object-contain {{ $imageClass }} drop-shadow-xl"
                            alt="{{ $title }}">
                    </div>
                    <div class="flex-1">
                        <h1
                            class="{{ $mode == 'delivery' ? 'text-[#5c4033]' : 'text-white' }} text-2xl md:text-3xl font-extrabold tracking-tight mb-1">
                            {{ $title }}
                        </h1>
                        <p
                            class="{{ $mode == 'delivery' ? 'text-[#8b6f5e]' : 'text-white/90' }} text-xs md:text-sm font-medium">
                            {{ $subtitle }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:max-w-xl">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/70 text-xs"></i>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari..."
                            class="w-full py-2.5 pl-10 pr-4 text-sm bg-white/20 border-0 rounded-full text-white placeholder-white/70 focus:ring-2 focus:ring-white/50 backdrop-blur-sm">
                    </div>

                    <button x-data @click="$dispatch('open-method-modal')"
                        class="{{ $mode == 'delivery' ? 'border-[#5c4033] text-[#5c4033] hover:bg-[#5c4033]/10' : 'border-white text-white hover:bg-white/20' }} border-2 rounded-full px-4 md:px-7 py-1.5 md:py-2 text-xs md:text-sm font-bold transition-all hover:scale-105 active:scale-95 shadow-sm">
                        Ubah Metode
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-12 lg:px-24">
        <!-- Location Info / Timeline -->
        <div class="py-5 bg-transparent border-b border-gray-200">
            <h3 class="font-bold text-[#2d3748] mb-5 text-lg md:text-xl">
                {{ $mode == 'pickup' ? 'Ambil pesananmu di' : 'Pesananmu dikirim dari' }}
            </h3>

            <div class="relative pl-1">
                @if($mode != 'pickup')
                    <!-- Vertical Dotted Line -->
                    <div class="absolute left-6 top-10 bottom-14 w-px border-l border-dashed border-gray-400"></div>
                @endif

                <!-- Store Node -->
                <div class="flex items-start gap-4 mb-6 relative z-10">
                    <div
                        class="w-10 h-10 bg-[#eedcd3] rounded-full flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm z-10">
                        <i class="fas fa-store text-[#5c4033] text-sm"></i>
                    </div>
                    <div class="flex-1 pt-1 border-b border-gray-200 pb-4">
                        <a href="{{ route('store-selection', ['mode' => $mode]) }}" wire:navigate class="block">
                            <h4 class="text-sm font-bold text-[#2d3748]">
                                {{ $selectedStore ? $selectedStore->name : 'Pilih Cabang' }}
                            </h4>
                            @if($mode != 'pickup')
                                <p class="text-xs mt-0.5 {{ $isOutOfBounds ? 'text-red-500' : 'text-gray-500' }}">
                                    @if($distance !== null)
                                        <span
                                            class="{{ $isOutOfBounds ? 'text-red-600 font-bold' : 'text-green-600 font-medium' }}">{{ $distance }}km</span>
                                        {{ $isOutOfBounds ? '• Diluar jangkauan' : 'dari lokasimu' }}
                                    @else
                                        Menghitung jarak...
                                    @endif
                                </p>
                                @if($distance !== null && !$isOutOfBounds)
                                    <p class="text-xs text-gray-500 mt-1">Estimasi ongkos kirim: <strong>Rp
                                            {{ number_format($shippingCost, 0, ',', '.') }}</strong></p>
                                @endif
                            @endif
                        </a>
                    </div>
                </div>

                @if($mode != 'pickup')
                    <!-- User Location Node -->
                    <div class="flex items-center gap-4 relative z-10 {{ $mode == 'delivery' ? 'mb-6' : '' }}">
                        <div
                            class="w-10 h-10 bg-[#e6f4ea] rounded-full flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm z-10">
                            <i class="fas fa-map-marker-alt text-green-600 text-sm"></i>
                        </div>
                        <a href="{{ route('pelanggan.alamat') }}" wire:navigate
                            class="flex-1 flex justify-between items-center cursor-pointer group">
                            <p class="text-sm text-gray-600 font-medium group-hover:text-[#5c4033] transition-colors">
                                {{ $activeAddressTitle ?: 'Pilih alamatmu terlebih dahulu (Klik di sini)' }}
                            </p>
                            <i
                                class="fas fa-crosshairs text-gray-400 text-sm group-hover:text-[#5c4033] transition-colors"></i>
                        </a>
                    </div>
                @endif

                @if($mode == 'delivery' && !$isOutOfBounds)
                    <!-- Detail Location Input (Image 3) -->
                    <div class="flex items-center gap-4 relative z-10">
                        <div
                            class="w-10 h-10 bg-[#e2e8f0] rounded-full flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm z-10">
                            <i class="fas fa-edit text-gray-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <input type="text" wire:model.live.debounce.500ms="detailAddress"
                                placeholder="Tambahkan detail lokasi"
                                class="w-full text-sm border border-gray-400 rounded-full px-5 py-2.5 focus:outline-none focus:border-[#5c4033] placeholder-gray-500 text-gray-700">
                        </div>
                    </div>
                @endif
            </div>

            @if($mode == 'delivery' || $mode == 'po')
                @if($isOutOfBounds)
                    <div
                        class="mt-6 bg-[#fff8f5] border border-[#f4dfd4] rounded-2xl p-5 text-center relative overflow-hidden shadow-sm">
                        <div
                            class="w-12 h-12 bg-white text-red-400 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-red-100">
                            <i class="fas fa-route text-lg"></i>
                        </div>

                        <h4 class="text-[#5c4033] font-bold text-sm mb-1">Wah, lokasimu terlalu jauh 🛵💨</h4>
                        <p class="text-xs text-gray-500 leading-relaxed mb-5 px-2">
                            Jarak pengiriman melebihi batas aman (5km). Yuk pilih opsi lain agar kualitas kue tetap terjaga!
                        </p>

                        <div class="flex items-center gap-3">
                            <button wire:click="$set('mode', 'pickup')"
                                class="flex-1 bg-white border-2 border-[#5c4033] text-[#5c4033] font-bold py-2.5 rounded-xl text-xs transition hover:bg-[#5c4033] hover:text-white">
                                Pick Up Saja
                            </button>
                            <a href="{{ route('store-selection', ['mode' => $mode]) }}" wire:navigate
                                class="flex-1 bg-[#5c4033] border-2 border-[#5c4033] text-white font-bold py-2.5 rounded-xl text-xs transition hover:bg-[#4a3328] hover:border-[#4a3328]">
                                Ganti Store
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mt-6 flex flex-col gap-2">
                        <div
                            class="bg-gradient-to-r from-[#f4dfd4]/60 to-white p-4 rounded-2xl border border-[#f4dfd4] flex items-center justify-between relative overflow-hidden shadow-sm">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#5c4033]"></div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-[#5c4033] shrink-0 border border-gray-50">
                                    <i class="fas fa-motorcycle text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mb-0.5">Estimasi Tiba
                                    </p>
                                    <p class="text-sm font-extrabold text-[#5c4033]">
                                        ~ {{ $eta ?? '...' }} Menit
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span
                                    class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-[10px] font-bold">
                                    <i class="fas fa-check-circle"></i> Siap Antar
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 px-2 mt-1">
                            <i class="fas fa-info-circle text-[#d93025] text-[10px] mt-0.5"></i>
                            <p class="text-[10px] text-gray-400 font-medium leading-relaxed">
                                Pastikan titik peta & detail alamat sudah sesuai agar kurir tidak nyasar ya!
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <main class="py-8">
            <!-- Categories Chips (Horizontal Scroll) -->
            <div class="flex gap-2 overflow-x-auto pb-2 mb-4 scrollbar-hide hide-scroll-bar"
                style="-ms-overflow-style: none; scrollbar-width: none;">
                <button wire:click="$set('selectedCategory', null)"
                    class="shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold border transition-colors 
                {{ $selectedCategory === null ? 'bg-[#5c4033] text-white border-[#5c4033]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                    <i class="fas fa-star text-yellow-400 mr-1"></i> Semua
                </button>
                @foreach($categories as $category)
                    <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold border transition-colors 
                            {{ $selectedCategory === $category->id ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-gray-100 text-gray-600 border-transparent hover:bg-gray-200' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Section Title -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-star text-yellow-400"></i> Produk Terlaris
                </h2>
                <span class="text-xs text-gray-400">{{ $products->total() }} item</span>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 gap-3 md:gap-6 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                @forelse($products as $product)
                    <div wire:key="product-{{ $product->id }}" wire:click="openProductDetail({{ $product->id }})"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col relative group cursor-pointer hover:shadow-md transition-shadow">

                        <!-- Badges -->
                        <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                            @if($loop->index < 3 && $products->currentPage() == 1)
                                <span
                                    class="bg-green-100 text-green-700 text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-medal"></i> Best Seller
                                </span>
                            @endif
                            @if($mode == 'po')
                                <span
                                    class="bg-purple-100 text-purple-700 text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm w-max">
                                    Pre-Order
                                </span>
                            @endif
                        </div>

                        <!-- Image -->
                        <div class="h-32 md:h-40 bg-gray-100 relative overflow-hidden shrink-0">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/eedcd3/5c4033?text=Kue' }}"
                                alt="{{ $product->name }}"
                                class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">
                        </div>

                        <!-- Content -->
                        <div class="p-3 flex flex-col flex-1">
                            <p class="text-[10px] text-gray-400 mb-0.5">{{ $product->category->name ?? 'Kategori' }}</p>
                            <h3 class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-2 flex-1">
                                {{ $product->name }}
                            </h3>

                            @if($mode == 'po')
                                <p class="text-[9px] text-red-500 mb-2">
                                    <i class="far fa-clock"></i> Tutup:
                                    {{ \Carbon\Carbon::parse($product->po_deadline)->format('d/m H:i') }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-sm font-bold text-[#5c4033]">
                                    Rp
                                    {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}
                                </span>
                                <button wire:click.stop="addToCart({{ $product->id }})"
                                    class="w-6 h-6 rounded-full bg-[#5c4033] text-white flex items-center justify-center hover:bg-[#4a332a] hover:scale-110 transition-transform active:scale-95">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center col-span-full">
                        <div class="inline-block p-6 bg-white border border-gray-100 shadow-md rounded-2xl">
                            <i class="mb-3 text-4xl text-gray-300 fas fa-box-open"></i>
                            <p class="text-sm font-medium text-gray-500">Belum ada produk untuk mode ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 mb-12">
                {{ $products->links() }}
            </div>
        </main>
    </div>

    <!-- Modal Metode Pemesanan (AlpineJS) -->
    <div x-data="{ open: false }" x-show="open" @open-method-modal.window="open = true" style="display: none;"
        class="fixed inset-0 z-[80] flex items-end justify-center sm:items-center">

        <!-- Backdrop -->
        <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"
            aria-hidden="true"></div>

        <!-- Modal Panel -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 sm:scale-100"
            x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden pb-6">

            <!-- Handle bar for mobile -->
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>

            <div class="px-6 py-4">
                <h3 class="text-xl font-bold text-center text-[#5c4033] mb-6">Pilih Metode Pemesanan</h3>

                <div class="flex flex-col gap-4">
                    <!-- Option: Pick Up -->
                    <a href="{{ route('ecommerce', ['mode' => 'pickup']) }}" wire:navigate
                        class="flex items-center p-3 rounded-2xl border-2 transition-all {{ $mode == 'pickup' ? 'border-blue-500 bg-blue-50/50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                        <div class="w-16 h-20 shrink-0 flex items-center justify-center relative">
                            <img src="{{ asset('images/pickup.png') }}"
                                class="object-contain h-full scale-125 drop-shadow-md origin-bottom">
                        </div>
                        <div class="flex-1 ml-4">
                            <h4 class="text-lg font-bold text-[#2d3748]">Pick Up</h4>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">Ambil di Store tanpa antri</p>
                        </div>
                        <div
                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 ml-2 {{ $mode == 'pickup' ? 'border-blue-500' : 'border-gray-300' }}">
                            @if($mode == 'pickup')
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            @endif
                        </div>
                    </a>

                    <!-- Option: Delivery -->
                    <a href="{{ route('ecommerce', ['mode' => 'delivery']) }}" wire:navigate
                        class="flex items-center p-3 rounded-2xl border-2 transition-all {{ $mode == 'delivery' ? 'border-blue-500 bg-blue-50/50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                        <div class="w-16 h-20 shrink-0 flex items-center justify-center relative">
                            <img src="{{ asset('images/delivery.png') }}"
                                class="object-contain h-full scale-125 drop-shadow-md origin-bottom">
                        </div>
                        <div class="flex-1 ml-4">
                            <h4 class="text-lg font-bold text-[#2d3748]">Delivery</h4>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">Garansi tepat waktu, dijamin!</p>
                        </div>
                        <div
                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 ml-2 {{ $mode == 'delivery' ? 'border-blue-500' : 'border-gray-300' }}">
                            @if($mode == 'delivery')
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal (AlpineJS) -->
    <div x-data="{ openDetail: false }" x-show="openDetail" @open-product-modal.window="openDetail = true"
        @close-product-modal.window="openDetail = false" style="display: none;"
        class="fixed inset-0 z-[80] flex items-end justify-center sm:items-center">

        <!-- Backdrop -->
        <div x-show="openDetail" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 backdrop-blur-sm"
            @click="openDetail = false" aria-hidden="true"></div>

        <!-- Modal Panel -->
        <div x-show="openDetail" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 sm:scale-100"
            x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <!-- Handle bar for mobile -->
            <div class="absolute top-4 left-0 right-0 z-10 flex justify-center sm:hidden">
                <div class="w-12 h-1.5 bg-gray-300/80 backdrop-blur-sm rounded-full"></div>
            </div>

            <!-- Close Button (Top Right) -->
            <button @click="openDetail = false"
                class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-black/20 text-white backdrop-blur-sm hover:bg-black/40 transition">
                <i class="fas fa-times text-sm"></i>
            </button>

            @if($selectedProductForDetail)
                <!-- Image Area -->
                <div class="h-64 bg-gray-100 relative shrink-0">
                    <img src="{{ $selectedProductForDetail->image ? asset('storage/' . $selectedProductForDetail->image) : 'https://placehold.co/400x400/eedcd3/5c4033?text=Kue' }}"
                        class="w-full h-full object-cover" alt="{{ $selectedProductForDetail->name }}">

                    @if($selectedProductForDetail->is_po && $mode == 'po')
                        <span
                            class="absolute bottom-4 left-4 bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            Pre-Order
                        </span>
                    @endif
                </div>

                <!-- Content Area (Scrollable) -->
                <div class="flex-1 overflow-y-auto px-6 py-5">
                    <p class="text-xs text-[#5c4033] font-bold mb-1 uppercase tracking-wider">
                        {{ $selectedProductForDetail->category->name ?? 'Kategori' }}
                    </p>
                    <h3 class="text-2xl font-extrabold text-[#2d3748] mb-2 leading-tight">
                        {{ $selectedProductForDetail->name }}
                    </h3>

                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-xl font-bold text-[#5c4033]">
                            Rp
                            {{ number_format($selectedProductForDetail->price - ($selectedProductForDetail->price * $selectedProductForDetail->discount / 100), 0, ',', '.') }}
                        </span>
                        @if($selectedProductForDetail->discount > 0)
                            <span class="text-sm text-gray-400 line-through">
                                Rp {{ number_format($selectedProductForDetail->price, 0, ',', '.') }}
                            </span>
                            <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                -{{ $selectedProductForDetail->discount }}%
                            </span>
                        @endif
                    </div>

                    <div class="prose prose-sm prose-[#5c4033] max-w-none text-gray-600 leading-relaxed mb-6">
                        @if($selectedProductForDetail->description)
                            {!! nl2br(e($selectedProductForDetail->description)) !!}
                        @else
                            <p class="italic text-gray-400">Belum ada deskripsi untuk produk ini.</p>
                        @endif
                    </div>

                    <!-- Variasi: Rasa -->
                    @if(!empty($selectedProductForDetail->flavors))
                        <div class="mb-5">
                            <h4 class="text-sm font-bold text-[#2d3748] mb-3">Pilihan Rasa <span class="text-red-500">*</span>
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedProductForDetail->flavors as $flavor)
                                    <label class="cursor-pointer relative">
                                        <input type="radio" wire:model="selectedFlavor" value="{{ $flavor }}" class="peer sr-only"
                                            name="flavor_selection">
                                        <div
                                            class="px-4 py-2 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-600 transition-all peer-checked:border-[#5c4033] peer-checked:bg-[#5c4033]/10 peer-checked:text-[#5c4033] hover:border-gray-200">
                                            {{ $flavor }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Variasi: Porsi -->
                    @if(!empty($selectedProductForDetail->portions))
                        <div class="mb-2">
                            <h4 class="text-sm font-bold text-[#2d3748] mb-3">Pilihan Porsi <span class="text-red-500">*</span>
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedProductForDetail->portions as $portion)
                                    <label class="cursor-pointer relative">
                                        <input type="radio" wire:model="selectedPortion" value="{{ $portion }}" class="peer sr-only"
                                            name="portion_selection">
                                        <div
                                            class="px-4 py-2 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-600 transition-all peer-checked:border-[#5c4033] peer-checked:bg-[#5c4033]/10 peer-checked:text-[#5c4033] hover:border-gray-200">
                                            {{ $portion }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer (Sticky Add to Cart) -->
                <div
                    class="px-6 pt-4 pb-8 sm:pb-4 border-t border-gray-100 bg-white shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] shrink-0">
                    <button wire:click="addToCart({{ $selectedProductForDetail->id }}, true)"
                        class="w-full bg-[#5c4033] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#5c4033]/30 transition hover:bg-[#4a3328] hover:shadow-xl active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-basket"></i> Tambah ke Keranjang
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Geolocation Script for Shop -->
    <!-- Geolocation Script removed as it's now handled in AddressCreate -->

    <style>
        .hide-scroll-bar::-webkit-scrollbar {
            display: none;
        }
    </style>
</div>
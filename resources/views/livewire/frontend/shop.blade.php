<div>
    <div class="relative py-6 mb-4 overflow-hidden text-white gradient-bg rounded-b-[2rem] shadow-lg">
        <div class="absolute inset-0 pointer-events-none opacity-10">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full filter blur-2xl float-animation"></div>
        </div>

        <div class="container relative z-10 px-4 mx-auto">
            <h1 class="mb-4 text-2xl font-bold text-center md:text-4xl">🎂 Pre-Order Kue</h1>

            <div class="flex flex-col gap-3 md:flex-row">

                <div class="relative w-full">
                    <div class="absolute text-purple-400 transform -translate-y-1/2 left-3 top-1/2">
                        <i class="text-sm fas fa-search"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kue..."
                        class="w-full py-2.5 pl-9 pr-4 text-sm transition-all bg-white/90 border-0 rounded-xl focus:ring-2 focus:ring-purple-300 text-gray-800 placeholder-gray-400 shadow-sm">
                </div>

                <div class="relative w-full md:w-64">
                    <div class="absolute text-purple-400 transform -translate-y-1/2 left-3 top-1/2">
                        <i class="text-sm fas fa-filter"></i>
                    </div>
                    <select wire:model.live="selectedCategory"
                        class="w-full py-2.5 pl-9 pr-8 text-sm transition-all bg-white/90 border-0 rounded-xl focus:ring-2 focus:ring-purple-300 text-gray-800 shadow-sm appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div
                        class="absolute text-xs text-purple-400 transform -translate-y-1/2 pointer-events-none right-3 top-1/2">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container px-3 pb-20 mx-auto md:px-6">

        <div class="grid grid-cols-2 gap-3 sm:gap-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @forelse($products as $product)
                <div wire:key="product-{{ $product->id }}"
                    class="flex flex-col overflow-hidden bg-white border border-gray-100 shadow-md rounded-xl group">

                    <div class="relative overflow-hidden bg-gray-100 h-36 sm:h-56 shrink-0">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/8b5cf6/ffffff?text=Kue' }}"
                            alt="{{ $product->name }}"
                            class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">

                        @if($product->discount > 0)
                            <div
                                class="absolute px-1.5 py-0.5 text-[10px] sm:text-xs font-bold text-white rounded bg-red-500 top-2 left-2 shadow-sm">
                                -{{ $product->discount }}%
                            </div>
                        @endif

                        <div
                            class="absolute px-1.5 py-0.5 text-[10px] sm:text-xs font-bold text-white rounded bg-purple-500/90 backdrop-blur-sm bottom-2 right-2 shadow-sm">
                            PO
                        </div>
                    </div>

                    <div class="flex flex-col flex-grow p-2.5 sm:p-4">
                        <h3
                            class="mb-1 text-xs font-medium text-gray-800 sm:text-base line-clamp-2 leading-tight min-h-[2rem]">
                            {{ $product->name }}
                        </h3>

                        <div class="mb-2">
                            <span class="text-sm font-bold text-purple-700 sm:text-lg">
                                Rp
                                {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}
                            </span>
                            @if($product->discount > 0)
                                <span class="block text-[10px] sm:text-xs text-gray-400 line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <div
                            class="mb-3 text-[10px] sm:text-xs text-red-500 bg-red-50 px-2 py-1 rounded w-fit flex items-center gap-1">
                            <i class="far fa-clock"></i>
                            <span>
                                Tutup: {{ \Carbon\Carbon::parse($product->po_deadline)->format('d/m H:i') }}
                            </span>

                        </div>

                        <div class="mt-auto">
                            <button wire:click="addToCart({{ $product->id }})"
                                class="w-full py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-white transition-all rounded-lg btn-gradient hover:shadow-md active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fas fa-plus"></i> Beli
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center col-span-full">
                    <div class="inline-block p-6 bg-white border border-gray-100 shadow-lg rounded-3xl">
                        <i class="mb-3 text-5xl text-gray-300 fas fa-cookie-bite"></i>
                        <p class="text-sm font-medium text-gray-500">Produk tidak ditemukan</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </main>
</div>

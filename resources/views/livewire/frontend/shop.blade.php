<div>
    <!-- Filter Bar -->
    <div class="flex items-center justify-between p-4 mb-6 bg-white rounded-lg shadow-sm">
        <div class="flex-1">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari produk PO..."
                class="w-full px-4 py-2 border rounded-lg md:w-1/2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="w-1/3 md:w-1/4">
            <select wire:model.live="selectedCategory"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @forelse($products as $product)
            <div wire:key="product-{{ $product->id }}"
                class="overflow-hidden transition-transform transform bg-white rounded-lg shadow-md hover:scale-105">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x200/e2e8f0/cbd5e0?text=Produk' }}"
                    alt="{{ $product->name }}" class="object-cover w-full h-48">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                    <p class="my-2 text-xl font-bold text-indigo-600">
                        Rp {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}
                    </p>
                    <div class="text-sm text-red-600">
                        <i class="mr-1 far fa-clock"></i>
                        PO Tutup: {{ \Carbon\Carbon::parse($product->po_deadline)->format('d M Y') }}
                    </div>

                    <button wire:click="addToCart({{ $product->id }})"
                        class="w-full px-4 py-2 mt-4 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="mr-2 fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        @empty
            <div class="py-12 text-center col-span-full">
                <p class="text-2xl text-gray-500">Tidak ada produk Pre-Order yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

    <!-- Notifikasi Toast (Opsional, tapi sangat bagus) -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                // (Anda bisa ganti ini dengan toast library favorit Anda)
                alert(data.message);
            });
        });
    </script>

    <script>
        window.addEventListener('confirm-logout', () => {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('executeLogout');
                }
            });
        });
    </script>



</div>

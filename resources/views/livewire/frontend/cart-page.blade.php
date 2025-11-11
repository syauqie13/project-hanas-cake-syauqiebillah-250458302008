<div class="p-6 bg-white rounded-lg shadow-md">
    <h1 class="mb-6 text-3xl font-bold">Keranjang Belanja Anda</h1>

    @if(empty($cartItems))
        <div class="py-12 text-center">
            <i class="mb-4 text-gray-300 fas fa-shopping-cart fa-4x"></i>
            <p class="mb-4 text-2xl text-gray-500">Keranjang Anda masih kosong.</p>
            <a href="{{ route('shop') }}" wire:navigate class="px-6 py-3 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Mulai Belanja (PO)
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="overflow-hidden border rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 font-medium text-left text-gray-600">Produk</th>
                                <th class="p-4 font-medium text-center text-gray-600">Jumlah</th>
                                <th class="p-4 font-medium text-right text-gray-600">Subtotal</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $id => $item)
                            <tr wire:key="cart-{{ $id }}" class="border-b">
                                <td class="flex items-center p-4">
                                    <img src="{{ $item['image'] ? asset('storage/'. $item['image']) : 'https://placehold.co/100x100/e2e8f0/cbd5e0?text=Produk' }}" alt="{{ $item['name'] }}" class="object-cover w-20 h-20 mr-4 rounded-md">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                        <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <input
                                        type="number"
                                        min="1"
                                        wire:change="updateQuantity('{{ $id }}', $event.target.value)"
                                        value="{{ $item['quantity'] }}"
                                        class="w-20 px-2 py-1 text-center border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                </td>
                                <td class="p-4 font-semibold text-right">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="removeFromCart('{{ $id }}')" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="p-6 border rounded-lg bg-gray-50">
                    <h2 class="mb-4 text-xl font-bold">Ringkasan</h2>
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-gray-600">Total Belanja</span>
                        <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    {{-- Tombol ini mengarah ke rute 'checkout' yang sudah kita daftarkan --}}
                    {{-- (Rute 'checkout' dilindungi oleh middleware 'is.pelanggan') --}}
                    <a href="{{ route('pelanggan.checkout') }}" class="block w-full px-6 py-3 text-center text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

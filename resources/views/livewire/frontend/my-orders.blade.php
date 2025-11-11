<div class="p-6 bg-white rounded-lg shadow-md">
    <h1 class="mb-6 text-3xl font-bold">Pesanan Saya</h1>

    @forelse($orders as $order)
        <div wire:key="order-{{ $order->id }}" class="mb-6 overflow-hidden border rounded-lg">
            <div class="flex flex-col items-start justify-between p-4 bg-gray-50 md:flex-row md:items-center">
                <div>
                    <h2 class="text-lg font-bold">Order ID: {{ $order->merchant_order_id ?? $order->id }}</h2>
                    <p class="text-sm text-gray-600">
                        Tanggal: {{ $order->tanggal->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <span class="text-lg font-bold">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="mt-2 md:mt-0">
                    @if($order->payment_status == 'paid' || $order->status == 'completed')
                        <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-200 rounded-full">
                            <i class="mr-1 fas fa-check-circle"></i> Lunas
                        </span>
                    @elseif($order->payment_status == 'pending')
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-full">
                            <i class="mr-1 fas fa-hourglass-half"></i> Menunggu Pembayaran
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-200 rounded-full">
                            <i class="mr-1 fas fa-times-circle"></i> Gagal/Dibatalkan
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-4">
                <h4 class="mb-2 font-semibold">Item yang dipesan:</h4>
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b' : '' }}">
                        <div class="flex items-center">
                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100/e2e8f0/cbd5e0?text=Produk' }}"
                                alt="{{ $item->product->name ?? 'N/A' }}" class="object-cover w-12 h-12 mr-3 rounded-md">
                            <div>
                                <p class="font-medium">{{ $item->product->name ?? 'Produk tidak tersedia' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->jumlah }} x Rp
                                    {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="font-medium text-gray-700">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tampilkan detail pengiriman jika ada --}}
            @if($order->shipping_address)
                <div class="p-4 border-t bg-gray-50">
                    <h4 class="mb-2 font-semibold">Detail Pengiriman</h4>
                    <p class="text-sm text-gray-600">
                        <strong>{{ $order->shipping_name }}</strong> ({{ $order->shipping_phone }})<br>
                        {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}
                    </p>
                </div>
            @endif

        </div>
    @empty
        <div class="py-12 text-center">
            <i class="mb-4 text-gray-300 fas fa-box-open fa-4x"></i>
            <p class="mb-4 text-2xl text-gray-500">Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ route('ecommerce') }}" wire:navigate
                class="px-6 py-3 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Mulai Belanja (PO)
            </a>
        </div>
    @endforelse

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>

<div>
    <h1 class="mb-6 text-3xl font-bold">Checkout</h1>

    {{-- Notifikasi Error --}}
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="placeOrder">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">

            <div class="p-6 bg-white rounded-lg shadow-md md:col-span-2">
                <h2 class="mb-4 text-xl font-bold">Detail Pengiriman & Tagihan</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input wire:model="name" id="name" type="text"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input wire:model="email" id="email" type="email"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input wire:model="phone" id="phone" type="tel"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea wire:model="address" id="address" rows="3"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                        <input wire:model="city" id="city" type="text"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('city') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input wire:model="postal_code" id="postal_code" type="text"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('postal_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="sticky p-6 border rounded-lg bg-gray-50 top-28">
                    <h2 class="mb-4 text-xl font-bold">Ringkasan Pesanan (PO)</h2>

                    <div class="mb-4 space-y-3 overflow-y-auto max-h-64">
                        @foreach($cartItems as $id => $item)
                            <div wire:key="summary-{{ $id }}" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/100x100/e2e8f0/cbd5e0?text=Produk' }}"
                                        alt="{{ $item['name'] }}" class="object-cover w-12 h-12 mr-3 rounded-md">
                                    <div>
                                        <p class="text-sm font-medium">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-500">x {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium">Rp
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4">

                    <div class="flex items-center justify-between mb-6">
                        <span class="text-lg text-gray-600">Total</span>
                        <span class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="block w-full px-6 py-3 text-center text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50">
                        <span wire:loading.remove><i class="mr-2 fas fa-shield-alt"></i> Bayar Sekarang</span>
                        <span wire:loading><i class="mr-2 fas fa-spinner fa-spin"></i> Memproses...</span>
                    </button>
                </div>
            </div>

        </div>
    </form>

    @push('js')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}">
            </script>

        <script>
            document.addEventListener('livewire:init', () => {
                // 2. Dengarkan event 'snap-show'
                Livewire.on('snap-show', (data) => {
                    if (data.snapToken) {
                        console.log('Snap token diterima, membuka popup...');
                        window.snap.pay(data.snapToken, {

                            // 3. onSuccess: Redirect ke halaman "Pesanan Saya"
                            onSuccess: function (result) {
                                console.log('Pembayaran Sukses:', result);
                                window.location.href = `{{ route('pelanggan.my-orders') }}`;
                            },

                            // 4. onPending: Redirect ke "Pesanan Saya" (untuk melihat status pending)
                            onPending: function (result) {
                                console.log('Pembayaran Pending:', result);
                                window.location.href = `{{ route('pelanggan.my-orders') }}`;
                            },

                            // 5. onError / onClose: Kembali ke Halaman Keranjang
                            onError: function (result) {
                                console.log('Pembayaran Error:', result);
                                // (Opsional) Beri notifikasi error
                                alert('Pembayaran Gagal. Silakan coba lagi.');
                                window.location.href = `{{ route('cart') }}`;
                            },
                            onClose: function () {
                                console.log('Popup ditutup.');
                                // (Opsional) Beri notifikasi
                                alert('Anda menutup jendela pembayaran.');
                                // Tidak perlu redirect, biarkan di halaman checkout
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</div>

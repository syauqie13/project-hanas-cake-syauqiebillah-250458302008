<div>
    <main class="container px-4 pt-6 pb-32 mx-auto md:px-6 md:py-8">

        <h1 class="mb-4 text-2xl font-bold text-gray-800 md:mb-6 md:text-3xl">Checkout</h1>

        {{-- Notifikasi Error --}}
        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg animate-pulse" role="alert">
                <span class="font-medium">Error!</span> {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="placeOrder">
            <div class="grid grid-cols-1 gap-6 md:gap-8 md:grid-cols-3">

                <!-- KOLOM KIRI (FORM) -->
                <div class="p-5 border shadow-sm md:shadow-xl md:p-8 bg-white/80 md:bg-white/70 backdrop-blur-md border-white/30 rounded-xl md:rounded-2xl md:col-span-2">

                    <!-- ============================================= -->
                    <!-- === BLOK METODE PENERIMAAN (BARU) === -->
                    <!-- ============================================= -->
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                        <div class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 md:text-xl">Metode Penerimaan</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <!-- Tombol Delivery -->
                        <button type="button"
                                wire:click="$set('delivery_type', 'delivery')"
                                class="p-4 rounded-lg border-2 transition-all duration-200 {{ $delivery_type == 'delivery' ? 'bg-purple-50 border-purple-600 shadow-lg' : 'bg-white border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center justify-center gap-3">
                                <i class="fas fa-motorcycle text-xl {{ $delivery_type == 'delivery' ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                <span class="font-semibold {{ $delivery_type == 'delivery' ? 'text-purple-800' : 'text-gray-700' }}">Dikirim (Delivery)</span>
                            </div>
                        </button>
                        <!-- Tombol Pickup -->
                        <button type="button"
                                wire:click="$set('delivery_type', 'pickup')"
                                class="p-4 rounded-lg border-2 transition-all duration-200 {{ $delivery_type == 'pickup' ? 'bg-purple-50 border-purple-600 shadow-lg' : 'bg-white border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center justify-center gap-3">
                                <i class="fas fa-store text-xl {{ $delivery_type == 'pickup' ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                <span class="font-semibold {{ $delivery_type == 'pickup' ? 'text-purple-800' : 'text-gray-700' }}">Ambil Sendiri</span>
                            </div>
                        </button>
                    </div>
                    <!-- ============================================= -->


                    <!-- === BAGIAN PENGIRIMAN (KONDISIONAL) === -->
                    @if ($delivery_type == 'delivery')
                    <div wire:key="delivery-section" class="animate-fade-in">
                        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                            <div class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Alamat Pengiriman</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Pilih Area / Zona Pengiriman</label>
                                <select wire:model.live="shipping_zone_id" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                    <option value="">-- Pilih Area --</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">
                                            {{ $zone->name }} ({{ $zone->price == 0 ? 'Gratis / Konfirmasi WA' : 'Rp ' . number_format($zone->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_zone_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            {{-- Input Alamat (Sama seperti kode Anda) --}}
                            <div class="md:col-span-2">
                                <label for="address" class="block mb-1.5 text-sm font-semibold text-gray-700">Alamat Lengkap</label>
                                <textarea wire:model="address" id="address" rows="3" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white" placeholder="Nama Jalan, No. Rumah, RT/RW..."></textarea>
                                @error('address') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="city" class="block mb-1.5 text-sm font-semibold text-gray-700">Kota</label>
                                <input wire:model="city" id="city" type="text" placeholder="Contoh: Jakarta" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('city') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="postal_code" class="block mb-1.5 text-sm font-semibold text-gray-700">Kode Pos</label>
                                <input wire:model="postal_code" id="postal_code" type="text" placeholder="12345" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('postal_code') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- === BAGIAN PICKUP (KONDISIONAL) === -->
                    @if ($delivery_type == 'pickup')
                    <div wire:key="pickup-section" class="animate-fade-in">
                        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                            <div class="flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-full">
                                <i class="fas fa-store"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Informasi Pengambilan</h2>
                        </div>
                        <div class="p-4 border border-green-200 bg-green-50 rounded-xl">
                            <h4 class="font-bold text-green-800">Pesanan Ambil di Toko</h4>
                            <p class="mt-1 text-sm text-green-700">Anda akan mengambil pesanan Anda di toko. Ongkos kirim otomatis Rp 0.</p>
                            <p class="mt-2 text-sm text-green-900"><strong>Alamat Toko:</strong><br>Hana Cake, Gang Masjid Rt 017 Rw 003 Desa Tegal Kunir Lor, Kecamatan Mauk, Kabupaten Tangerang.</p>
                        </div>
                    </div>
                    @endif

                    <!-- === DATA PEMESAN (SELALU TAMPIL) === -->
                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                            <div class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                                <i class="fas fa-user-alt"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Data Pemesan</h2>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="name" class="block mb-1.5 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                <input wire:model="name" id="name_billing" type="text" placeholder="Nama Pemesan" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('name') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block mb-1.5 text-sm font-semibold text-gray-700">Email</label>
                                <input wire:model="email" id="email_billing" type="email" placeholder="email@contoh.com" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('email') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block mb-1.5 text-sm font-semibold text-gray-700">No. WhatsApp</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-500 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50">+62</span>
                                    <input wire:model="phone" id="phone_billing" type="tel" placeholder="81234567890" class="flex-1 block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-r-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                </div>
                                @error('phone') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            {{-- Alamat Pemesan (Billing) --}}
                            @if($delivery_type == 'pickup')
                            <div class="md:col-span-2">
                                <label for="address" class="block mb-1.5 text-sm font-semibold text-gray-700">Alamat Pemesan (Tagihan)</label>
                                <textarea wire:model="address" id="address_billing" rows="3" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white" placeholder="Alamat untuk data tagihan"></textarea>
                                @error('address') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="city" class="block mb-1.5 text-sm font-semibold text-gray-700">Kota</label>
                                <input wire:model="city" id="city_billing" type="text" placeholder="Contoh: Jakarta" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('city') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="postal_code" class="block mb-1.5 text-sm font-semibold text-gray-700">Kode Pos</label>
                                <input wire:model="postal_code" id="postal_code_billing" type="text" placeholder="12345" class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('postal_code') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (RINGKASAN) -->
                <div class="md:col-span-1">
                    <div class="sticky p-5 border shadow-sm md:shadow-xl md:p-8 bg-white/80 md:bg-white/70 backdrop-blur-md border-white/30 rounded-xl md:rounded-2xl top-24">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-100">
                            <div class="flex items-center justify-center w-8 h-8 text-pink-600 bg-pink-100 rounded-full">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Ringkasan</h2>
                        </div>

                        <div class="pr-1 mb-4 space-y-3 overflow-y-auto max-h-64 custom-scrollbar">
                            @forelse($cartItems as $id => $item)
                                {{-- (Tampilan item keranjang Anda - sudah benar) --}}
                                <div wire:key="summary-{{ $id }}" class="flex gap-3">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/100x100/e2e8f0/cbd5e0?text=Kue' }}"
                                        alt="{{ $item['name'] }}"
                                        class="object-cover border border-gray-100 rounded-lg w-14 h-14">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                                        <div class="flex items-center justify-between mt-1">
                                            <p class="text-xs text-gray-500">x {{ $item['quantity'] }}</p>
                                            <span class="text-sm font-semibold text-gray-700">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="py-4 text-sm text-center text-gray-500">Keranjang kosong.</p>
                            @endforelse
                        </div>

                        <div class="pt-4 mt-2 border-t border-gray-200 border-dashed">
                            <div class="flex items-center justify-between mb-2 text-sm">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <!-- === TAMPILAN ONGKIR (DIPERBARUI) === -->
                            <div class="flex items-center justify-between mb-4 text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-semibold text-indigo-600">
                                    @if($delivery_type == 'pickup')
                                        Gratis (Ambil Sendiri)
                                    @elseif($shipping_cost > 0)
                                        Rp {{ number_format($shipping_cost, 0, ',', '.') }}
                                    @elseif($shipping_zone_id)
                                        Gratis
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <!-- =================================== -->

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-base font-bold text-gray-800">Total Tagihan</span>
                                <span class="text-xl font-bold text-transparent bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- === CHECKBOX KONFIRMASI (DIPERBARUI) === -->
                        @if($delivery_type == 'delivery' && $requires_confirmation)
                            <div class="p-3 mt-4 border border-yellow-200 rounded-lg bg-yellow-50">
                                <label class="flex items-start space-x-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="confirmed_shipping"
                                        class="mt-1 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-xs text-yellow-800">
                                        <strong>Konfirmasi Diperlukan:</strong> Lokasi ini di luar jangkauan standar. Saya
                                        sudah menghubungi admin via WhatsApp untuk menyepakati pengiriman.
                                    </span>
                                </label>
                                @error('confirmed_shipping') <span class="block mt-1 text-xs text-red-500">Anda harus
                                mencentang ini.</span> @enderror
                            </div>
                        @endif
                        <!-- ======================================= -->

                        <button type="submit" wire:loading.attr="disabled"
                            class="hidden md:flex items-center justify-center w-full px-6 py-3.5 mt-6 font-bold text-white rounded-xl shadow-lg btn-gradient hover:shadow-xl transform transition hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="placeOrder"><i class="mr-2 fas fa-lock"></i> Bayar Sekarang</span>
                            <span wire:loading wire:target="placeOrder"><i class="mr-2 fas fa-spinner fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Bottom Bar (Diperbarui) -->
            <div class="fixed bottom-0 left-0 right-0 z-50 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:hidden">
                <div class="flex gap-4">
                    <div class="flex flex-col justify-center flex-1">
                        <span class="text-xs text-gray-500">Total Pembayaran</span>
                        <span class="text-lg font-bold text-purple-700">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex items-center justify-center flex-1 px-4 py-3 font-bold text-white transition-transform shadow-md rounded-xl btn-gradient active:scale-95 disabled:opacity-70">
                        <span wire:loading.remove wire:target="placeOrder">
                            Bayar <i class="ml-2 fas fa-chevron-right"></i>
                        </span>
                        <span wire:loading wire:target="placeOrder">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>

        </form>
    </main>

    {{-- JavaScript (Tetap Sama, sudah benar) --}}
    @push('js')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('snap-show', (data) => {
                if (data.snapToken) {
                    const merchantOrderId = data.merchantOrderId;
                    window.snap.pay(data.snapToken, {
                        onSuccess: function (result) {
                            window.location.href = `/pelanggan/my-orders`;
                        },
                        onPending: function (result) {
                            window.location.href = `/pelanggan/my-orders`;
                        },
                        onError: function (result) {
                            alert('Pembayaran Gagal. Silakan coba lagi.');
                        },
                        onClose: function () {
                            console.log('Popup ditutup.');
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</div>

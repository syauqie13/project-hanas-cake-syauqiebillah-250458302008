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
                <div
                    class="p-5 border shadow-sm md:shadow-xl md:p-8 bg-white/80 md:bg-white/70 backdrop-blur-md border-white/30 rounded-xl md:rounded-2xl md:col-span-2">

                    <!-- ============================================= -->
                    <!-- === BLOK METODE PENERIMAAN (BARU) === -->
                    <!-- ============================================= -->
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                        <div
                            class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 md:text-xl">Metode Penerimaan</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <!-- Tombol Delivery -->
                        <button type="button" wire:click="$set('delivery_type', 'delivery')"
                            class="p-4 rounded-lg border-2 transition-all duration-200 {{ $delivery_type == 'delivery' ? 'bg-purple-50 border-purple-600 shadow-lg' : 'bg-white border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center justify-center gap-3">
                                <i
                                    class="fas fa-motorcycle text-xl {{ $delivery_type == 'delivery' ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                <span
                                    class="font-semibold {{ $delivery_type == 'delivery' ? 'text-purple-800' : 'text-gray-700' }}">Dikirim
                                    (Delivery)</span>
                            </div>
                        </button>
                        <!-- Tombol Pickup -->
                        <button type="button" wire:click="$set('delivery_type', 'pickup')"
                            class="p-4 rounded-lg border-2 transition-all duration-200 {{ $delivery_type == 'pickup' ? 'bg-purple-50 border-purple-600 shadow-lg' : 'bg-white border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center justify-center gap-3">
                                <i
                                    class="fas fa-store text-xl {{ $delivery_type == 'pickup' ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                <span
                                    class="font-semibold {{ $delivery_type == 'pickup' ? 'text-purple-800' : 'text-gray-700' }}">Ambil
                                    Sendiri</span>
                            </div>
                        </button>
                    </div>
                    <!-- ============================================= -->


                    <!-- === BAGIAN PENGIRIMAN (KONDISIONAL) === -->
                    @if ($delivery_type == 'delivery')
                        <div wire:key="delivery-section" class="animate-fade-in">
                            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                                <div
                                    class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 md:text-xl">Alamat Pengiriman</h2>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Pilih Area / Zona
                                        Pengiriman</label>
                                    <select wire:model.live="shipping_zone_id"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                        <option value="">-- Pilih Area --</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}">
                                                {{ $zone->name }}
                                                ({{ $zone->price == 0 ? 'Gratis / Konfirmasi WA' : 'Rp ' . number_format($zone->price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipping_zone_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Input Alamat (Sama seperti kode Anda) --}}
                                <div class="md:col-span-2">
                                    <label for="address" class="block mb-1.5 text-sm font-semibold text-gray-700">Alamat
                                        Lengkap</label>
                                    <textarea wire:model="address" id="address" rows="3"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white"
                                        placeholder="Nama Jalan, No. Rumah, RT/RW..."></textarea>
                                    @error('address') <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city" class="block mb-1.5 text-sm font-semibold text-gray-700">Kota</label>
                                    <input wire:model="city" id="city" type="text" placeholder="Contoh: Jakarta"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                    @error('city') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block mb-1.5 text-sm font-semibold text-gray-700">Kode
                                        Pos</label>
                                    <input wire:model="postal_code" id="postal_code" type="text" placeholder="12345"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                    @error('postal_code') <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- === BAGIAN PICKUP (KONDISIONAL) === -->
                    @if ($delivery_type == 'pickup')
                        <div wire:key="pickup-section" class="animate-fade-in">
                            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                                <div
                                    class="flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-full">
                                    <i class="fas fa-store"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 md:text-xl">Informasi Pengambilan</h2>
                            </div>
                            <div class="p-4 border border-green-200 bg-green-50 rounded-xl">
                                <h4 class="font-bold text-green-800">Pesanan Ambil di Toko</h4>
                                <p class="mt-1 text-sm text-green-700">Anda akan mengambil pesanan Anda di toko. Ongkos
                                    kirim otomatis Rp 0.</p>
                                <p class="mt-2 text-sm text-green-900"><strong>Alamat Toko:</strong><br>Hana Cake, Gang
                                    Masjid Rt 017 Rw 003 Desa Tegal Kunir Lor, Kecamatan Mauk, Kabupaten Tangerang.</p>
                            </div>
                        </div>
                    @endif

                    <!-- === DATA PEMESAN (SELALU TAMPIL) === -->
                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                            <div
                                class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                                <i class="fas fa-user-alt"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Data Pemesan</h2>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="name" class="block mb-1.5 text-sm font-semibold text-gray-700">Nama
                                    Lengkap</label>
                                <input wire:model="name" id="name_billing" type="text" placeholder="Nama Pemesan"
                                    class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('name') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email"
                                    class="block mb-1.5 text-sm font-semibold text-gray-700">Email</label>
                                <input wire:model="email" id="email_billing" type="email" placeholder="email@contoh.com"
                                    class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                @error('email') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block mb-1.5 text-sm font-semibold text-gray-700">No.
                                    WhatsApp</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-500 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50">+62</span>
                                    <input wire:model="phone" id="phone_billing" type="tel" placeholder="81234567890"
                                        class="flex-1 block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-r-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                </div>
                                @error('phone') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            {{-- Alamat Pemesan (Billing) --}}
                            @if($delivery_type == 'pickup')
                                <div class="md:col-span-2">
                                    <label for="address" class="block mb-1.5 text-sm font-semibold text-gray-700">Alamat
                                        Pemesan (Tagihan)</label>
                                    <textarea wire:model="address" id="address_billing" rows="3"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white"
                                        placeholder="Alamat untuk data tagihan"></textarea>
                                    @error('address') <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city" class="block mb-1.5 text-sm font-semibold text-gray-700">Kota</label>
                                    <input wire:model="city" id="city_billing" type="text" placeholder="Contoh: Jakarta"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                    @error('city') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block mb-1.5 text-sm font-semibold text-gray-700">Kode
                                        Pos</label>
                                    <input wire:model="postal_code" id="postal_code_billing" type="text" placeholder="12345"
                                        class="block w-full px-4 py-2.5 text-sm md:text-base transition duration-150 border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 bg-white">
                                    @error('postal_code') <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (RINGKASAN) -->
                <div class="md:col-span-1">
                    <div
                        class="sticky p-5 border shadow-sm md:shadow-xl md:p-8 bg-white/80 md:bg-white/70 backdrop-blur-md border-white/30 rounded-xl md:rounded-2xl top-24">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-100">
                            <div
                                class="flex items-center justify-center w-8 h-8 text-pink-600 bg-pink-100 rounded-full">
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

                        <!-- === INPUT VOUCHER === -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Punya Kode Voucher?</label>
                            @if(!$appliedVoucherId)
                                <div class="flex items-start gap-2">
                                    <div class="flex-grow">
                                        <input type="text" wire:model="voucherCode" placeholder="Masukkan kode voucher"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 outline-none">
                                        @error('voucherCode') <span
                                        class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="button" wire:click="applyVoucher"
                                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                                        Gunakan
                                    </button>
                                </div>
                            @else
                                <div
                                    class="flex items-center justify-between p-3 bg-green-50 text-green-800 rounded-lg border border-green-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span class="text-sm">Voucher <strong>{{ $voucherCode }}</strong> aktif!</span>
                                    </div>
                                    <button type="button" wire:click="removeVoucher"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Hapus
                                    </button>
                                </div>
                            @endif
                        </div>
                        <!-- ===================== -->

                        @if(count($availableVouchers) > 0)
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Atau Pilih Promo:</p>
                                <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar pr-1">
                                    @foreach($availableVouchers as $v)
                                                    @php
                                                        // Logika pengecekan apakah subtotal cukup untuk voucher ini
                                                        $isLocked = $subtotal < $v->min_purchase;
                                                    @endphp

                                                    <div wire:key="voucher-{{ $v->id }}" class="border rounded-lg p-3 flex justify-between items-center transition 
                                        {{ $appliedVoucherId == $v->id ? 'border-indigo-500 bg-indigo-100 ring-1 ring-indigo-500' : 'border-indigo-200 bg-indigo-50 hover:bg-indigo-100' }}
                                        {{ $isLocked ? 'opacity-50 grayscale pointer-events-none' : '' }}">

                                                        <div>
                                                            <p
                                                                class="text-sm font-bold {{ $appliedVoucherId == $v->id ? 'text-indigo-900' : 'text-indigo-800' }}">
                                                                {{ $v->code }}
                                                            </p>
                                                            <p
                                                                class="text-xs {{ $appliedVoucherId == $v->id ? 'text-indigo-700' : 'text-indigo-600' }}">
                                                                Diskon
                                                                {{ $v->type == 'nominal' ? 'Rp ' . number_format($v->value, 0, ',', '.') : $v->value . '%' }}

                                                                @if($v->min_purchase)
                                                                    <br>
                                                                    <span
                                                                        class="{{ $isLocked ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                                                                        Min. Belanja Rp {{ number_format($v->min_purchase, 0, ',', '.') }}
                                                                    </span>
                                                                @endif
                                                            </p>
                                                        </div>

                                                        <div>
                                                            @if($appliedVoucherId == $v->id)
                                                                <button type="button" wire:click="removeVoucher"
                                                                    class="px-3 py-1.5 text-xs font-bold text-red-600 bg-white border border-red-400 rounded shadow-sm hover:bg-red-50 transition">
                                                                    Batalkan
                                                                </button>
                                                            @elseif($isLocked)
                                                                <button type="button" disabled
                                                                    class="px-3 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 border border-gray-200 rounded shadow-sm cursor-not-allowed">
                                                                    Klaim
                                                                </button>
                                                            @elseif(in_array($v->id, $claimedVoucherIds))
                                                                <button type="button" wire:click="useClaimedVoucher({{ $v->id }})"
                                                                    class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-white border border-indigo-500 rounded shadow-sm hover:bg-indigo-50 transition">
                                                                    Pakai
                                                                </button>
                                                            @else
                                                                <button type="button" wire:click="claimVoucher({{ $v->id }})"
                                                                    class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50 transition">
                                                                    Klaim
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- ======================== -->

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

                            @if($discountAmount > 0)
                                <div class="flex items-center justify-between mb-4 text-sm text-green-600">
                                    <span>Diskon Voucher</span>
                                    <span class="font-semibold">- Rp
                                        {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <!-- =================================== -->

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-base font-bold text-gray-800">Total Tagihan</span>
                                <span
                                    class="text-xl font-bold text-transparent bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text">
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

                        <button type="button" @click="$dispatch('open-pin-modal')"
                            class="hidden md:flex items-center justify-center w-full px-6 py-3.5 mt-6 font-bold text-white rounded-xl shadow-lg btn-gradient hover:shadow-xl transform transition hover:-translate-y-0.5">
                            <span><i class="mr-2 fas fa-lock"></i> Bayar Sekarang</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Bottom Bar (Diperbarui) -->
            <div
                class="fixed bottom-0 left-0 right-0 z-50 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:hidden">
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

    <div x-data="{ open: false }" @open-pin-modal.window="open = true" @close-pin-modal.window="open = false"
        x-show="open" class="fixed inset-0 z-[999] overflow-y-auto" style="display: none;">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all">
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt fa-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Keamanan Transaksi</h3>
                    <p class="text-sm text-gray-500 mt-1">Masukkan 6-digit PIN Pembayaran Anda</p>
                </div>

                <div class="mt-6">
                    <input type="password" wire:model="pin_input" maxlength="6" placeholder="••••••"
                        class="w-full text-center text-3xl tracking-[1em] font-bold py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 transition-all">

                    @error('pin_input')
                        <span class="text-red-500 text-xs mt-2 block text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400">Lupa PIN?
                        <button type="button" wire:click="sendResetPinEmail"
                            class="text-purple-600 font-bold hover:underline">
                            Klik di sini untuk reset
                        </button>
                    </p>
                </div>

                <div class="mt-8 flex flex-col gap-3">
                    <button wire:click="validatePinAndPlaceOrder" wire:loading.attr="disabled"
                        class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="validatePinAndPlaceOrder">Konfirmasi Pembayaran</span>
                        <span wire:loading wire:target="validatePinAndPlaceOrder"><i
                                class="fas fa-spinner fa-spin mr-2"></i> Memvalidasi...</span>
                    </button>

                    <button @click="open = false"
                        class="text-sm text-gray-400 font-semibold hover:text-gray-600 transition-all">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript (Tetap Sama, sudah benar) --}}
    @push('js')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
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
<div class="w-full min-h-screen bg-gray-50 pb-32 font-sans text-gray-800 antialiased relative">
    
    <div class="bg-white sticky top-0 z-30 px-4 py-4 flex items-center border-b border-gray-100 md:px-12 lg:px-24">
        <a href="{{ route('ecommerce') }}" wire:navigate class="w-10 h-10 flex items-center justify-center text-[#5c4033] hover:bg-gray-50 rounded-full transition">
            <i class="fas fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-lg md:text-xl font-bold text-[#5c4033] flex-1 text-center pr-10">Checkout</h1>
    </div>

    <div class="bg-[#f4dfd4]/40 px-4 py-4 flex items-center justify-between border-b border-[#f4dfd4]/60 md:px-12 lg:px-24">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm shrink-0">
                @if($delivery_type == 'pickup')
                    <img src="{{ asset('images/pickup.png') }}" class="w-9 h-9 object-contain" alt="Pick Up" onerror="this.src='https://placehold.co/40x40?text=PU'">
                @else
                    <img src="{{ asset('images/delivery.png') }}" class="w-9 h-9 object-contain" alt="Delivery" onerror="this.src='https://placehold.co/40x40?text=DEL'">
                @endif
            </div>
            <div>
                <h2 class="text-base font-bold text-[#5c4033] capitalize">{{ $delivery_type == 'po' ? 'Pre-Order' : $delivery_type }}</h2>
                <p class="text-[11px] text-[#8b6f5e] font-medium">
                    {{ $delivery_type == 'pickup' ? 'Ambil di Store tanpa antri' : 'Garansi tepat waktu, dijamin!' }}
                </p>
            </div>
        </div>
        <a href="{{ route('ecommerce', ['mode' => $delivery_type]) }}" wire:navigate class="bg-white border border-[#5c4033] text-[#5c4033] text-xs font-bold rounded-full px-4 py-1.5 hover:bg-[#5c4033] hover:text-white transition-all shadow-sm">
            Ubah
        </a>
    </div>

    <div class="max-w-2xl mx-auto mt-3 space-y-2.5 px-0 sm:px-4">
        
        <div class="bg-white p-5 shadow-sm sm:rounded-2xl border border-gray-100">
            <h3 class="font-bold text-gray-800 text-sm md:text-base mb-4">
                {{ $delivery_type == 'pickup' ? 'Ambil pesananmu di' : 'Pesananmu dikirim dari' }}
            </h3>

            <div class="relative pl-1">
                @if($delivery_type != 'pickup')
                    <div class="absolute left-4.5 top-8 bottom-8 w-px border-l border-dashed border-gray-300"></div>
                @endif

                <div class="flex items-start gap-4 mb-5 relative z-10">
                    <div class="w-9 h-9 bg-[#f4dfd4] rounded-full flex items-center justify-center shrink-0 border-2 border-white shadow-sm">
                        <i class="fas fa-store text-[#5c4033] text-xs"></i>
                    </div>
                    <div class="flex-1 border-b border-gray-100 pb-3">
                        <h4 class="text-sm font-bold text-gray-800">{{ $selectedStore ? $selectedStore->name : 'Mencari Cabang...' }}</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            @if($delivery_type != 'pickup' && $distance !== null)
                                <span class="{{ $isOutOfBounds ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">{{ $distance }}km</span> 
                                {{ $isOutOfBounds ? '• Di luar jangkauan kurir' : 'dari lokasimu' }}
                            @else
                                Cabang Terpilih Hana's Cake
                            @endif
                        </p>
                    </div>
                </div>

                @if($delivery_type != 'pickup')
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-9 h-9 bg-green-50 rounded-full flex items-center justify-center shrink-0 border-2 border-white shadow-sm">
                            <i class="fas fa-map-marker-alt text-green-600 text-xs"></i>
                        </div>
                        <a href="{{ route('pelanggan.alamat') }}" wire:navigate class="flex-1 flex justify-between items-center group py-1">
                            <div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-[#5c4033] transition-colors">
                                    {{ $address ? explode(' - ', $address)[0] : 'Pilih Alamat Tujuan' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                    {{ $address ? (explode(' - ', $address)[1] ?? '') : 'Klik di sini untuk menentukan titik pengiriman' }}
                                </p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-[#5c4033] transition-colors pl-2"></i>
                        </a>
                    </div>
                @endif
            </div>

            @error('distance')
                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-xl text-xs font-semibold text-red-600 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                </div>
            @enderror
            @error('address')
                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-xl text-xs font-semibold text-red-600 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="bg-white p-5 shadow-sm sm:rounded-2xl border border-gray-100">
            <h3 class="font-bold text-gray-800 text-sm md:text-base mb-4">Detail Pesanan</h3>
            
            <div class="divide-y divide-gray-100">
                @foreach($cartItems as $key => $item)
                    <div class="flex items-start gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                            <img src="{{ isset($item['image']) ? asset('storage/' . $item['image']) : 'https://placehold.co/150x150/eedcd3/5c4033?text=Kue' }}" class="w-full h-full object-cover" alt="{{ $item['name'] }}">
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $item['name'] }}</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ isset($item['flavor']) ? 'Rasa: '.$item['flavor'] : '' }} 
                            </p>
                            <p class="text-sm font-extrabold text-[#5c4033] mt-2">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs font-bold text-gray-500">x{{ $item['quantity'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-500 font-medium">Mau nambah menu kue yang lain?</p>
                <a href="{{ route('ecommerce') }}" wire:navigate class="border border-gray-300 text-gray-700 px-4 py-1.5 rounded-full text-xs font-bold hover:bg-gray-50 transition-all flex items-center gap-1">
                    Tambah
                </a>
            </div>

            @if($delivery_type == 'delivery' || $delivery_type == 'po')
                <div class="mt-3 p-3 bg-gray-50 rounded-xl flex items-center gap-3 border border-gray-100">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-[#5c4033] shrink-0">
                        <i class="fas fa-shopping-bag text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-xs font-bold text-gray-700">Tas Belanja Box Kue</h5>
                        <p class="text-[10px] text-gray-400 mt-0.5">Ditambahkan otomatis untuk keamanan delivery</p>
                    </div>
                </div>
            @endif
        </div>



        <div class="bg-white p-5 shadow-sm sm:rounded-2xl border border-gray-100 space-y-2.5 text-xs md:text-sm">
            <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1">Rincian Pembayaran</h3>
            <div class="flex justify-between text-gray-500 font-medium">
                <span>Subtotal Kue</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            @if($delivery_type != 'pickup')
                <div class="flex justify-between text-gray-500 font-medium">
                    <span>Biaya Pengiriman ({{ $distance ?? '0' }} km)</span>
                    <span>Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                </div>
            @endif
            <hr class="border-gray-100 my-2">
            <div class="flex justify-between font-extrabold text-base text-[#5c4033] pt-1">
                <span>Total Tagihan</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-4 py-3.5 flex items-center justify-between z-40 md:max-w-xl md:mx-auto md:bottom-4 md:rounded-2xl md:border md:shadow-lg">
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Total Pembayaran</p>
            <p class="text-lg font-black text-[#5c4033] mt-0.5">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        
        <button x-data type="button" @click="$dispatch('open-pin-modal')" 
            {{ ($isOutOfBounds || ($delivery_type != 'pickup' && !$address)) ? 'disabled' : '' }}
            class="bg-[#5c4033] text-white text-sm font-bold px-8 py-3.5 rounded-xl shadow-md shadow-[#5c4033]/20 hover:bg-[#4a3328] transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
            Bayar Sekarang
        </button>
    </div>

    <div x-data="{ openPin: false }" x-show="openPin" @open-pin-modal.window="openPin = true" @close-pin-modal.window="openPin = false" style="display: none;" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center">
        <div x-show="openPin" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="openPin = false"></div>
        <div x-show="openPin" x-transition.scale.95 class="relative w-full max-w-md bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden p-6 z-10 text-center">
            <i class="fas fa-shield-alt text-3xl text-[#5c4033] mb-2"></i>
            <h3 class="text-base font-extrabold text-gray-800">Keamanan Pembayaran</h3>
            <p class="text-xs text-gray-400 mt-1 mb-5">Masukkan 6 digit PIN Akun Hana's Cake Anda</p>
            
            <form wire:submit.prevent="validatePinAndPlaceOrder" class="space-y-4">
                <input type="password" wire:model="pin_input" maxlength="6" placeholder="******" class="tracking-widest text-center text-xl font-bold w-full py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#5c4033] focus:bg-white">
                @error('pin_input') <span class="text-red-500 text-xs block font-medium">{{ $message }}</span> @enderror
                
                <div class="flex flex-col gap-2 pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="w-full bg-[#5c4033] text-white font-bold py-3 rounded-xl text-xs transition hover:bg-[#4a3328]">
                        <span wire:loading.remove wire:target="validatePinAndPlaceOrder">Konfirmasi Pembayaran</span>
                        <span wire:loading wire:target="validatePinAndPlaceOrder"><i class="fas fa-circle-notch fa-spin"></i> Memproses Token...</span>
                    </button>
                    <button type="button" wire:click="sendResetPinEmail" class="text-xs font-bold text-gray-400 py-1.5 hover:text-[#5c4033]">Lupa PIN Pembayaran?</button>
                </div>
            </form>
        </div>
    </div>

</div>

@assets
<script type="text/javascript" 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
@endassets

@script
<script>
    $wire.on('snap-show', (event) => {
        // Livewire 3 bisa mengirim data dalam bentuk object langsung atau dibungkus array.
        let token = event[0]?.snapToken || event?.snapToken;
        let orderId = event[0]?.orderId || event?.orderId;
        let deliveryType = event[0]?.deliveryType || event?.deliveryType;
        
        if (typeof snap !== 'undefined') {
            snap.pay(token, {
                onSuccess: function(result) {
                    if (deliveryType === 'pickup') {
                        window.location.href = '/pelanggan/orders/' + orderId + '/success';
                    } else {
                        window.location.href = '/pelanggan/my-orders';
                    }
                },
                onPending: function(result) {
                    if (deliveryType === 'pickup') {
                        window.location.href = '/pelanggan/orders/' + orderId + '/success';
                    } else {
                        window.location.href = '/pelanggan/my-orders';
                    }
                },
                onError: function(result) { alert("Pembayaran gagal diproses!"); },
                onClose: function() { alert('Anda menutup layar pembayaran tanpa menyelesaikan transaksi.'); }
            });
        } else {
            alert('Gagal memuat library Midtrans. Pastikan koneksi internet stabil dan refresh halaman.');
        }
    });
</script>
@endscript
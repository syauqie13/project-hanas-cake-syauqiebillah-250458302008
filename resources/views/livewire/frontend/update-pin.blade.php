<div>
    <div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-[#5C3A33]">Ubah PIN Pembayaran</h3>
            <p class="text-sm text-gray-500">Gunakan PIN yang kuat untuk melindungi transaksi Anda.</p>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="updatePin" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">PIN Saat Ini</label>
                <input type="password" wire:model="current_pin" maxlength="6" placeholder="••••••"
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-200 transition-all text-center tracking-[1em] font-bold">
                @error('current_pin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <hr class="border-gray-100">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">PIN Baru (6 Digit)</label>
                <input type="password" wire:model="new_pin" maxlength="6" placeholder="••••••"
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-200 transition-all text-center tracking-[1em] font-bold">
                @error('new_pin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi PIN Baru</label>
                <input type="password" wire:model="new_pin_confirmation" maxlength="6" placeholder="••••••"
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-200 transition-all text-center tracking-[1em] font-bold">
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-purple-100">
                <span wire:loading.remove wire:target="updatePin">Perbarui PIN</span>
                <span wire:loading wire:target="updatePin"><i class="fas fa-spinner fa-spin mr-2"></i>
                    Memproses...</span>
            </button>
        </form>
    </div>
</div>
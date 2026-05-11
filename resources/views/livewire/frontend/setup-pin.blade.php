<div class="min-h-screen bg-white flex flex-col antialiased">
    
    <div class="bg-white border-b border-gray-100 px-4 py-4 flex items-center sticky top-0 z-50">
        @if($step == 2)
            <button wire:click="$set('step', 1)" class="text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        @endif
        <h1 class="flex-1 text-center text-xl font-bold text-[#5C3A33] {{ $step == 2 ? 'mr-6' : '' }}">
            @if($is_reset)
                {{ $step == 1 ? 'Reset PIN' : 'Konfirmasi PIN Baru' }}
            @else
                {{ $step == 1 ? 'Buat PIN' : 'Konfirmasi PIN' }}
            @endif
        </h1>
    </div>

    <div class="flex-1 flex flex-col justify-between p-6 md:p-10 max-w-lg mx-auto w-full" x-data="{ step: @entangle('step') }">
        
        <div class="mt-8 text-center">
            <h2 class="text-2xl font-bold text-[#5C3A33]">
                @if($is_reset)
                    {{ $step == 1 ? 'Masukkan PIN Baru' : 'Ulangi PIN Baru' }}
                @else
                    {{ $step == 1 ? 'Buat PIN Keamanan' : 'Konfirmasi PIN' }}
                @endif
            </h2>
            <p class="text-gray-500 mt-2 text-sm">
                @if($is_reset)
                    Silakan masukkan PIN baru untuk mengganti PIN yang lama.
                @else
                    PIN ini akan digunakan untuk setiap transaksi Anda di Hana's Cake.
                @endif
            </p>
        </div>

        <div class="mt-12 flex-1 relative">
            @if($step == 1)
                <input type="number" wire:model.live="pin" id="pinInput" maxlength="6"
                       oninput="if (this.value.length > 6) this.value = this.value.slice(0, 6);"
                       class="absolute opacity-0 inset-0 w-full h-full cursor-default border-none ring-0 focus:ring-0">
            @else
                <input type="number" wire:model.live="pin_confirmation" id="pinConfirmInput" maxlength="6"
                       oninput="if (this.value.length > 6) this.value = this.value.slice(0, 6);"
                       class="absolute opacity-0 inset-0 w-full h-full cursor-default border-none ring-0 focus:ring-0">
            @endif

            <div class="grid grid-cols-6 gap-3 justify-items-center pointer-events-none">
                @for ($i = 0; $i < 6; $i++)
                    @php 
                        $val = $step == 1 ? $pin : $pin_confirmation;
                        $char = substr($val, $i, 1);
                    @endphp
                    <div class="w-full h-14 flex items-center justify-center text-2xl font-bold border-2 {{ $char !== '' ? 'border-purple-600 bg-purple-50 text-purple-900' : 'border-gray-200 bg-white text-gray-400' }} rounded-xl transition-all">
                        {{ $char !== '' ? $char : '•' }}
                    </div>
                @endfor
            </div>

            <div class="mt-6 text-center h-4">
                @error('pin') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                @error('pin_confirmation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-12 pb-8">
            @if($step == 1)
                <button wire:click="goToStep2" 
                        {{ strlen($pin) < 6 ? 'disabled' : '' }}
                        class="w-full py-4 bg-purple-600 disabled:bg-[#EBDDD6] disabled:text-[#A79A95] text-white font-bold rounded-xl shadow-lg transition-all active:scale-95">
                    Lanjutkan
                </button>
            @else
                <button wire:click="savePin" 
                        {{ strlen($pin_confirmation) < 6 ? 'disabled' : '' }}
                        class="w-full py-4 bg-purple-600 disabled:bg-[#EBDDD6] disabled:text-[#A79A95] text-white font-bold rounded-xl shadow-lg transition-all active:scale-95">
                    <span wire:loading.remove wire:target="savePin">
                        {{ $is_reset ? 'Reset PIN Sekarang' : 'Simpan PIN' }}
                    </span>
                    <span wire:loading wire:target="savePin">Menyimpan...</span>
                </button>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('click', () => {
            let el = document.getElementById('pinInput') || document.getElementById('pinConfirmInput');
            if(el) el.focus();
        });
        window.addEventListener('load', () => {
            let el = document.getElementById('pinInput') || document.getElementById('pinConfirmInput');
            if(el) el.focus();
        });
    </script>
</div>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md bg-white min-h-[100dvh] sm:min-h-0 sm:rounded-2xl sm:shadow-lg flex flex-col relative overflow-hidden">
        
        <div class="flex items-center justify-center relative pt-8 pb-6 px-6">
            <button type="button" wire:click="logout" class="absolute left-6 text-gray-500 hover:text-[#5A3D31] transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h1 class="text-2xl font-semibold text-[#5A3D31]">Verifikasi</h1>
        </div>

        <div class="flex-1 px-8 pt-4 pb-8 flex flex-col justify-between">
            
            <form wire:submit="verifyCode" class="flex flex-col h-full">
                <div class="space-y-6 flex-1">
                    <h2 class="text-2xl font-semibold text-[#5A3D31]">Masukan Kode Verifikasi</h2>
                    
                    <p class="text-sm text-gray-600 leading-relaxed px-1">
                        Kami telah mengirimkan 6 digit kode verifikasi melalui 
                        <span class="font-semibold text-gray-900">{{ auth()->user()->email ?? 'email Anda' }}</span>.
                    </p>

                    <div class="pt-6">
                        <input id="verificationCode" type="text" wire:model="verificationCode" maxlength="6" autofocus required
                            placeholder="------"
                            class="block w-full border-0 border-b-2 {{ $errors->has('verificationCode') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-3 text-center text-4xl tracking-[0.5em] text-gray-900 focus:ring-0 focus:border-[#5A3D31] transition-colors"
                            autocomplete="one-time-code">
                        
                        @error('verificationCode')
                            <span class="text-red-500 text-sm mt-2 block text-center font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="flex justify-between items-center text-sm text-gray-500 pt-6">
                        <button type="button" wire:click="resendVerification" wire:loading.attr="disabled"
                            class="font-medium hover:text-[#5A3D31] hover:underline disabled:opacity-60 transition">
                            <span wire:loading.remove wire:target="resendVerification">Kirim Ulang Kode</span>
                            <span wire:loading wire:target="resendVerification">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...
                            </span>
                        </button>
                    </div>
                </div>

                <div class="mt-10"></div>

                <div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-[#5A3D31] hover:bg-[#4a3127] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5A3D31] transition-colors disabled:opacity-70">
                        
                        <span wire:loading.remove wire:target="verifyCode">Lanjutkan</span>
                        <span wire:loading wire:target="verifyCode">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memverifikasi...
                        </span>
                    </button>

                    <p class="mt-4 text-center text-[11px] text-gray-600 leading-relaxed px-2">
                        Dengan masuk hana's cake, kamu telah menyetujui<br>
                        <a href="#" class="font-bold text-[#5A3D31] hover:underline">Syarat & Ketentuan</a> dan 
                        <a href="#" class="font-bold text-[#5A3D31] hover:underline">Kebijakan Privasi</a>
                    </p>

                    <div class="mt-6 text-center text-sm text-gray-600 hover:text-[#5A3D31] transition">
                        <button type="button" wire:click="logout" class="font-medium hover:underline">
                            Ubah akun / metode verifikasi
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- Script SweetAlert --}}
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail.icon,
                    title: 'Notifikasi',
                    text: event.detail.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endpush
</div>
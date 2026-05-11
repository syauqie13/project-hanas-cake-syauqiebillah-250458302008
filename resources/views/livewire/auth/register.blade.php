<div class="min-h-screen bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md bg-white min-h-[100dvh] sm:min-h-0 sm:rounded-2xl sm:shadow-lg flex flex-col relative overflow-hidden">
        
        <div class="flex items-center justify-center relative pt-8 pb-6 px-6">
            <a href="{{ route('login') }}" class="absolute left-6 text-gray-500 hover:text-[#5A3D31] transition">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="text-2xl font-semibold text-[#5A3D31]">Daftar</h1>
        </div>

        <div class="flex-1 px-8 pt-4 pb-8 flex flex-col justify-between">
            <form wire:submit.prevent="register" novalidate>
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#5A3D31]">Masukan Nama</label>
                        <div class="relative mt-1">
                            <input id="name" type="text" wire:model="name" required placeholder="Nama*" 
                                class="block w-full border-0 border-b {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors"
                                autocomplete="name" autofocus>
                        </div>
                        @error('name') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#5A3D31]">Alamat Email</label>
                        <div class="mt-1">
                            <input id="email" type="email" wire:model="email" required placeholder="example@gmail.com" 
                                class="block w-full border-0 border-b {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors"
                                autocomplete="username">
                        </div>
                        @error('email') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#5A3D31]">Password</label>
                        <div class="mt-1">
                            <input id="password" type="password" wire:model="password" required placeholder="Min. 8 karakter" 
                                class="block w-full border-0 border-b {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors"
                                autocomplete="new-password">
                        </div>
                        @error('password') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#5A3D31]">Konfirmasi Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" type="password" wire:model="password_confirmation" required placeholder="Samakan password" 
                                class="block w-full border-0 border-b {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors"
                                autocomplete="new-password">
                        </div>
                        @error('password_confirmation') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <div class="mt-10"></div>

                <div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-[#5A3D31] hover:bg-[#4a3127] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5A3D31] transition-colors disabled:opacity-70">
                        
                        <span wire:loading.remove>Lanjutkan</span>
                        
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                        </span>
                    </button>

                    <p class="mt-4 text-center text-[11px] text-gray-600 leading-relaxed px-2">
                        Dengan masuk hana's cake, kamu telah menyetujui<br>
                        <a href="#" class="font-bold text-[#5A3D31] hover:underline">Syarat & Ketentuan</a> dan 
                        <a href="#" class="font-bold text-[#5A3D31] hover:underline">Kebijakan Privasi</a>
                    </p>

                    <div class="mt-6 text-center text-sm text-gray-500">
                        Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-[#5A3D31] hover:underline">Masuk</a>
                    </div>
                    <div class="mt-2 text-center text-xs text-gray-400">
                        Copyright &copy; {{ date('Y') }} Hana's Cake.<br>All Rights Reserved.
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
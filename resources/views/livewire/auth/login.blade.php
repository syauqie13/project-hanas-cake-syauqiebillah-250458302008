<div class="min-h-screen bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md bg-white min-h-[100dvh] sm:min-h-0 sm:rounded-2xl sm:shadow-lg flex flex-col relative overflow-hidden">
        
        <div class="flex items-center justify-center relative pt-8 pb-6 px-6">
            <a href="{{ url('/') }}" class="absolute left-6 text-gray-500 hover:text-[#5A3D31] transition">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="text-2xl font-semibold text-[#5A3D31]">Masuk</h1>
        </div>

        <div class="flex-1 px-8 pt-4 pb-8 flex flex-col justify-between">
            <form wire:submit.prevent="login" novalidate class="flex flex-col h-full">

                <div class="space-y-6 flex-1">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#5A3D31]">Alamat email</label>
                        <div class="relative mt-1">
                            <input id="email" type="email" wire:model="email" required placeholder="Alamat email" 
                                class="block w-full border-0 border-b {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors"
                                autocomplete="email" autofocus>
                        </div>
                        @error('email') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-semibold text-[#5A3D31]">Password</label>
                        <div class="relative mt-1">
                            <input id="password" x-bind:type="showPassword ? 'text' : 'password'" wire:model="password" required placeholder="Masukkan password" 
                                class="block w-full border-0 border-b {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} bg-transparent px-0 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 focus:border-[#5A3D31] sm:text-sm transition-colors pr-8"
                                autocomplete="current-password">
                            
                            <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                class="absolute right-0 bottom-2 text-gray-400 hover:text-[#5A3D31] focus:outline-none transition-colors">
                                <i class="far" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i>
                            </button>
                        </div>
                        @error('password') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <input id="remember-me" type="checkbox" wire:model="remember" 
                            class="h-4 w-4 text-[#5A3D31] focus:ring-[#5A3D31] border-gray-300 rounded cursor-pointer transition">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                            Remember Me
                        </label>
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
                        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#5A3D31] hover:underline">Daftar di sini</a>
                    </div>
                    <div class="mt-2 text-center text-xs text-gray-400">
                        Copyright &copy; {{ date('Y') }} Hana's Cake.<br>All Rights Reserved.
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
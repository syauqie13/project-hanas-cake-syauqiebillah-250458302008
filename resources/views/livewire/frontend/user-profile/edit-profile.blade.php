<div x-data="{ step: 'edit' }" class="w-full min-h-screen md:min-h-fit bg-[#f8f9fa] md:bg-white pb-36 md:pb-10 font-sans text-gray-800 antialiased relative max-w-md md:max-w-4xl mx-auto shadow-sm md:shadow-xl md:rounded-2xl md:mt-10 md:border md:border-gray-100">
    
    <div x-show="step === 'edit'" x-transition.opacity>
        <div class="px-6 py-6 flex items-center justify-between border-b border-transparent md:border-gray-100">
            <a href="{{ route('pelanggan.profile') }}" wire:navigate class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </a>
            <h1 class="text-lg font-medium text-[#4a3328]">Akun Saya</h1>
            <div class="w-5"></div> </div>

        <div class="flex flex-col items-center mt-4 md:mt-8">
            <span class="bg-[#eedcd3] text-[#5c4033] text-[11px] font-medium px-5 py-2 rounded-full shadow-sm mb-6">
                Member sejak : {{ auth()->user()->created_at->translatedFormat('d F Y') }}
            </span>

            <div class="relative">
                <div class="w-28 h-28 md:w-36 md:h-36 rounded-full overflow-hidden shadow-md bg-gray-200">
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($oldImage)
                        <img src="{{ asset('storage/' . $oldImage) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=eedcd3&color=5c4033" class="w-full h-full object-cover">
                    @endif
                </div>
                <label class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/3 w-9 h-9 md:w-10 md:h-10 bg-[#eedcd3] rounded-full shadow-md flex items-center justify-center cursor-pointer border-2 border-white hover:bg-[#e4c9ba] transition">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-sm"></i>
                    <input type="file" wire:model="image" class="hidden">
                </label>
            </div>
        </div>

        <div class="px-6 mt-12 space-y-7 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-10 md:gap-y-8 md:px-12">
            
            <div class="relative border-b border-gray-400 md:border-gray-300 pb-2">
                <label class="block text-[11px] md:text-xs text-gray-500 mb-1">Username</label>
                <input type="text" wire:model="name" class="w-full bg-transparent outline-none text-gray-800 text-base md:text-sm font-medium pr-10">
                <div class="absolute right-0 bottom-2 w-7 h-7 bg-[#eedcd3] rounded-full flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-[10px]"></i>
                </div>
                @error('name') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>

            <div class="relative border-b border-gray-400 md:border-gray-300 pb-2">
                <label class="block text-[11px] md:text-xs text-gray-500 mb-1">Email</label>
                <input type="email" wire:model="email" class="w-full bg-transparent outline-none text-gray-800 text-base md:text-sm font-medium pr-10">
                <div class="absolute right-0 bottom-2 w-7 h-7 bg-[#eedcd3] rounded-full flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-[10px]"></i>
                </div>
                @error('email') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>

            <div class="relative border-b border-gray-400 md:border-gray-300 pb-2">
                <label class="block text-[11px] md:text-xs text-gray-500 mb-1">Tanggal Lahir</label>
                <input type="date" wire:model="birth_date" class="w-full bg-transparent outline-none text-gray-800 text-base md:text-sm font-medium pr-10 appearance-none">
                <div class="absolute right-0 bottom-2 w-7 h-7 bg-[#eedcd3] rounded-full flex items-center justify-center pointer-events-none">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-[10px]"></i>
                </div>
                @error('birth_date') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>

            <div class="relative border-b border-gray-400 md:border-gray-300 pb-2">
                <label class="block text-[11px] md:text-xs text-gray-500 mb-1">Jenis Kelamin</label>
                <select wire:model="gender" class="w-full bg-transparent outline-none text-gray-800 text-base md:text-sm font-medium pr-10 appearance-none">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                <div class="absolute right-0 bottom-2 w-7 h-7 bg-[#eedcd3] rounded-full flex items-center justify-center pointer-events-none">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-[10px]"></i>
                </div>
                @error('gender') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>

            <div class="relative border-b border-gray-400 md:border-gray-300 pb-2 md:col-span-2">
                <label class="block text-[11px] md:text-xs text-gray-500 mb-1">Nomor Telepon</label>
                <input type="text" wire:model="phone" class="w-full bg-transparent outline-none text-gray-800 text-base md:text-sm font-medium pr-10">
                <div class="absolute right-0 bottom-2 w-7 h-7 bg-[#eedcd3] rounded-full flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-[#5c4033] text-[10px]"></i>
                </div>
                @error('phone') <span class="text-red-500 text-xs absolute -bottom-5 left-0">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 w-full max-w-md mx-auto z-[99] flex flex-col bg-white 
                    md:static md:max-w-none md:flex-row md:justify-end md:gap-4 md:bg-transparent md:px-12 md:mt-10 md:pb-6">
            <button @click="step = 'delete'" type="button" class="w-full md:w-auto md:px-8 md:rounded-xl bg-[#ffebee] text-[#ef4444] py-4 md:py-3 flex items-center justify-center gap-2 text-[13px] tracking-wide font-bold hover:bg-red-100 transition">
                <i class="far fa-trash-alt text-lg md:text-base"></i> HAPUS AKUN
            </button>
            <button wire:click="updateProfile" type="button" class="w-full md:w-auto md:px-12 md:rounded-xl bg-[#1c6b38] text-white font-medium py-4 md:py-3 text-[15px] hover:bg-[#15532b] transition shadow-md shadow-green-900/10">
                <span wire:loading.remove wire:target="updateProfile">Simpan</span>
                <span wire:loading wire:target="updateProfile"><i class="fas fa-circle-notch fa-spin"></i> Menyimpan...</span>
            </button>
        </div>
    </div>


    <div x-show="step === 'delete'" x-cloak x-transition.opacity>
        <div class="px-6 py-6 flex items-center gap-4 border-b border-transparent md:border-gray-100">
            <button @click="step = 'edit'" type="button" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-lg font-medium text-[#4a3328]">Hapus Akun Saya</h1>
        </div>

        <div class="px-6 md:px-12 mt-4 md:mt-6">
            <h3 class="text-[13px] font-bold text-gray-800 mb-1">ALASAN HAPUS AKUN</h3>
            <p class="text-xs text-gray-500 mb-6 leading-relaxed">Sampaikan alasanmu agar kami bisa meningkatkan layanan yang lebih baik lagi</p>

            <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
                @foreach(['Saya ingin memulai akun dari awal', 'Akun saya sering error', 'Privasi akun kurang terjaga', 'Lainnya'] as $reason)
                <label class="flex items-center gap-4 p-4 border border-gray-200 hover:border-gray-300 rounded-lg cursor-pointer bg-transparent transition">
                    <div class="relative flex items-center justify-center w-5 h-5">
                        <input type="radio" wire:model.live="deleteReason" value="{{ $reason }}" class="peer sr-only">
                        <div class="w-5 h-5 border-2 border-gray-400 rounded-full peer-checked:border-[#3b82f6] transition-colors"></div>
                        <div class="absolute w-2.5 h-2.5 bg-[#3b82f6] rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                    </div>
                    <span class="text-[13px] text-gray-700">{{ $reason }}</span>
                </label>
                @endforeach
            </div>

            @if($deleteReason === 'Lainnya')
            <div class="mt-8 md:mt-10 md:w-1/2">
                <h3 class="text-[13px] font-bold text-gray-800 mb-4">ALASAN LAINNYA</h3>
                <div class="relative border-b border-gray-400 pb-2">
                    <input type="text" wire:model="otherReason" placeholder="Alasan kamu*" class="w-full bg-transparent outline-none text-gray-800 text-sm placeholder-gray-400 pr-10">
                    <div class="absolute right-0 bottom-2 w-7 h-7 bg-transparent border border-[#4a3328] rounded-full flex items-center justify-center pointer-events-none">
                        <i class="fas fa-pencil-alt text-[#4a3328] text-[10px]"></i>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 mt-1.5">Pastikan alasan kamu berjumlah minimal 3 karakter</p>
            </div>
            @endif
        </div>

        <div class="fixed bottom-0 left-0 right-0 w-full max-w-md mx-auto z-[99] flex flex-col bg-white shadow-[0_-4px_15px_rgba(0,0,0,0.05)]
                    md:static md:max-w-none md:flex-row md:justify-end md:gap-4 md:bg-transparent md:shadow-none md:px-12 md:mt-12 md:pb-6">
            <button @click="step = 'edit'" type="button" class="w-full md:w-auto md:px-10 md:rounded-xl md:bg-gray-100 bg-white text-[#4a3328] md:text-gray-700 font-medium py-4 md:py-3 text-[13px] border-t border-gray-100 md:border-none hover:bg-gray-200 transition">
                TIDAK JADI
            </button>
            <button wire:click="deleteAccount" 
                @if($deleteReason === 'Lainnya' && strlen($otherReason) < 3) disabled @endif
                @if(!$deleteReason) disabled @endif
                type="button" class="w-full md:w-auto md:px-10 md:rounded-xl bg-[#ff2c2c] text-white font-medium py-4 md:py-3 text-[14px] disabled:opacity-50 disabled:cursor-not-allowed hover:bg-red-600 transition shadow-md shadow-red-900/10">
                <span wire:loading.remove wire:target="deleteAccount">HAPUS AKUN</span>
                <span wire:loading wire:target="deleteAccount"><i class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
            </button>
        </div>
    </div>
</div>
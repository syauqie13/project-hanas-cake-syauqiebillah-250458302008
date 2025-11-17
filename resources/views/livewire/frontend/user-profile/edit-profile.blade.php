<div>
    <div class="min-h-screen py-6 bg-gray-50 md:py-12 md:bg-gradient-to-br md:from-purple-50 md:via-pink-50 md:to-blue-50">
        <main class="container max-w-4xl px-4 mx-auto">

            <div class="mb-6 text-center md:mb-8 md:text-left">
                <h1 class="text-2xl font-bold text-gray-800 md:text-3xl">Edit Profil</h1>
                <p class="mt-1 text-sm text-gray-500 md:text-base">Perbarui informasi pribadi Anda</p>
            </div>

            <div class="overflow-hidden bg-white border border-gray-100 shadow-md md:shadow-xl rounded-xl md:rounded-2xl">

                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h2 class="font-semibold text-gray-800">Form Data Diri</h2>
                    </div>
                </div>

                <div class="p-5 md:p-8">
                    <form wire:submit.prevent="updateProfile">

                        <!-- Bagian Foto Profil (Sesuai kode Anda) -->
                        <div class="flex flex-col items-center gap-6 p-4 mb-8 border border-purple-100 md:flex-row md:items-start bg-purple-50/50 rounded-xl">
                            <div class="relative group shrink-0">
                                <div class="w-24 h-24 overflow-hidden border-4 border-white rounded-full shadow-lg md:w-28 md:h-28 ring-2 ring-purple-100">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                                    @elseif ($oldImage)
                                        <img src="{{ asset('storage/'."/" . $oldImage) }}" class="object-cover w-full h-full">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=random&size=128"
                                            class="object-cover w-full h-full">
                                    @endif
                                </div>
                                <div wire:loading wire:target="image"
                                    class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 backdrop-blur-sm">
                                    <i class="text-white fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            <div class="flex-1 w-full text-center md:text-left">
                                <label class="block mb-2 text-sm font-bold text-gray-700">Foto Profil</label>
                                <div class="flex flex-col gap-3">
                                    <label for="avatar-upload"
                                        class="w-full md:w-auto px-4 py-2.5 text-sm font-medium text-purple-700 transition bg-white border border-purple-200 rounded-lg shadow-sm cursor-pointer hover:bg-purple-50 hover:border-purple-300 flex items-center justify-center gap-2 active:scale-95 transform duration-200">
                                        <i class="fas fa-camera"></i> Pilih Foto Baru
                                    </label>
                                    <input id="avatar-upload" type="file" wire:model="image" class="hidden" accept="image/*">
                                    <span class="text-xs leading-relaxed text-gray-500">
                                        Format: JPG, PNG, GIF.<br class="md:hidden"> Maksimal ukuran 2MB.
                                    </span>
                                </div>
                                @error('image') <span class="block p-2 mt-2 text-xs font-medium text-red-500 rounded bg-red-50">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Grid Form Utama -->
                        <div class="grid grid-cols-1 gap-5 md:gap-6 md:grid-cols-2">

                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-xs text-gray-400 fas fa-user"></i>
                                    </div>
                                    <input type="text" wire:model="name"
                                        class="w-full pl-9 py-2.5 text-sm md:text-base border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition">
                                </div>
                                @error('name') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-xs text-gray-400 fas fa-envelope"></i>
                                    </div>
                                    <input type="email" wire:model="email"
                                        class="w-full pl-9 py-2.5 text-sm md:text-base border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition">
                                </div>
                                @error('email') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- No. WhatsApp -->
                            <div class="md:col-span-2">
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">WhatsApp</label>
                                <div class="flex rounded-lg shadow-sm">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-500 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50">
                                        +62
                                    </span>
                                    <input type="text" wire:model="phone" placeholder="81234567890"
                                        class="flex-1 block w-full py-2.5 text-sm md:text-base border-gray-300 rounded-r-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition">
                                </div>
                                @error('phone') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Alamat Utama -->
                            <div class="md:col-span-2">
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Alamat Utama</label>
                                <textarea wire:model="address" rows="3"
                                    class="w-full py-2.5 text-sm md:text-base border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition"
                                    placeholder="Nama Jalan, Nomor Rumah, RT/RW..."></textarea>
                                @error('address') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- ================================== -->
                            <!-- === KOTA & KODE POS DITAMBAHKAN === -->
                            <!-- ================================== -->
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Kota / Kabupaten</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-xs text-gray-400 fas fa-city"></i>
                                    </div>
                                    <input type="text" wire:model="city" placeholder="Contoh: Tangerang"
                                        class="w-full pl-9 py-2.5 text-sm md:text-base border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition">
                                </div>
                                @error('city') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Kode Pos</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-xs text-gray-400 fas fa-map-pin"></i>
                                    </div>
                                    <input type="text" wire:model="postal_code" placeholder="15530"
                                        class="w-full pl-9 py-2.5 text-sm md:text-base border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition">
                                </div>
                                @error('postal_code') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <!-- ================================== -->
                            <!-- === AKHIR BAGIAN BARU === -->
                            <!-- ================================== -->

                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col-reverse justify-end gap-3 pt-6 mt-8 border-t border-gray-100 md:flex-row">
                            <a href="{{ route('pelanggan.profile') }}" wire:navigate
                                class="px-6 py-3 text-sm font-medium text-center text-gray-600 transition bg-white border border-gray-300 rounded-full hover:bg-gray-50 active:scale-95">
                                Batal
                            </a>

                            <button type="submit"
                                class="flex items-center justify-center px-8 py-3 font-bold text-white transition transform rounded-full shadow-lg btn-gradient active:scale-95 hover:shadow-xl"
                                wire:loading.attr="disabled" wire:target="updateProfile, image">

                                <span wire:loading.remove wire:target="updateProfile, image" class="flex items-center">
                                    <i class="mr-2 fas fa-save"></i> Simpan Perubahan
                                </span>
                                <span wire:loading wire:target="image">
                                    <i class="mr-2 fas fa-spinner fa-spin"></i> Mengupload...
                                </span>
                                <span wire:loading wire:target="updateProfile">
                                    <i class="mr-2 fas fa-circle-notch fa-spin"></i> Menyimpan...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

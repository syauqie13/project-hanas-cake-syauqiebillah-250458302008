<div class="w-full min-h-screen bg-[#fcfcfc] pb-32 font-sans text-gray-800 antialiased max-w-md mx-auto shadow-sm md:shadow-xl md:rounded-2xl md:mt-10 md:border md:border-gray-100 relative">
    
    <!-- Header -->
    <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100 bg-[#eedcd3] rounded-t-2xl">
        <a href="{{ route('pelanggan.profile') }}" wire:navigate class="text-[#5c4033] hover:text-gray-800 transition">
            <i class="fas fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-[#5c4033]">{{ __('Kebijakan Privasi') }}</h1>
        <div class="w-5"></div>
    </div>

    <!-- Content -->
    <div class="px-6 py-6 space-y-6 overflow-y-auto max-h-[75vh]">
        <p class="text-xs text-gray-500 leading-relaxed">
            {{ __('Terakhir diperbarui: 19 Mei 2026') }}. {{ __('Hana\'s Cake sangat menghargai dan melindungi kerahasiaan data pribadi pelanggan kami. Halaman ini menjelaskan bagaimana kami mengumpulkan dan melindungi data Anda.') }}
        </p>

        <!-- Section 1 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">1</span>
                {{ __('Informasi Yang Kami Kumpulkan') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Kami mengumpulkan data nama lengkap, nomor HP aktif, alamat pengiriman, serta titik koordinat GPS agar sistem kurir kami dapat memperkirakan jarak pengantaran pesanan secara akurat.') }}
            </p>
        </div>

        <!-- Section 2 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">2</span>
                {{ __('Kerahasiaan PIN & Sandi') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Semua PIN transaksi dan sandi akun dienkripsi secara satu arah di server kami menggunakan teknologi hashing berstandar industri. Tidak ada satu pun staf atau admin kami yang dapat melihat PIN keamanan Anda.') }}
            </p>
        </div>

        <!-- Section 3 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">3</span>
                {{ __('Integrasi Pihak Ketiga') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Untuk urusan pembayaran digital, data transaksi diteruskan secara aman ke payment gateway Midtrans melalui jalur terenkripsi SSL/HTTPS demi menjaga keamanan transaksi kartu kredit maupun dompet digital.') }}
            </p>
        </div>

        <!-- Section 4 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">4</span>
                {{ __('Notifikasi Pesanan') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Berdasarkan preferensi yang Anda atur di halaman pengaturan profil, kami akan mengirimkan detail resi, kuitansi, dan status pelacakan kue pesanan Anda melalui Email dan notifikasi WhatsApp.') }}
            </p>
        </div>
    </div>

</div>

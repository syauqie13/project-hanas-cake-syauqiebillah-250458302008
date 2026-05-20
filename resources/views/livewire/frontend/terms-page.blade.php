<div class="w-full min-h-screen bg-[#fcfcfc] pb-32 font-sans text-gray-800 antialiased max-w-md mx-auto shadow-sm md:shadow-xl md:rounded-2xl md:mt-10 md:border md:border-gray-100 relative">
    
    <!-- Header -->
    <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100 bg-[#eedcd3] rounded-t-2xl">
        <a href="{{ route('pelanggan.profile') }}" wire:navigate class="text-[#5c4033] hover:text-gray-800 transition">
            <i class="fas fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-[#5c4033]">{{ __('Syarat & Ketentuan') }}</h1>
        <div class="w-5"></div>
    </div>

    <!-- Content -->
    <div class="px-6 py-6 space-y-6 overflow-y-auto max-h-[75vh]">
        <p class="text-xs text-gray-500 leading-relaxed">
            {{ __('Terakhir diperbarui: 19 Mei 2026') }}. {{ __('Selamat datang di Hana\'s Cake. Mohon baca Syarat dan Ketentuan ini secara seksama sebelum melakukan pemesanan.') }}
        </p>

        <!-- Section 1 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">1</span>
                {{ __('Pemesanan & Pre-Order') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Semua produk kue kami diproduksi secara higienis menggunakan bahan berkualitas tinggi. Untuk produk custom cake atau pre-order, pesanan wajib dilakukan minimal 1-2 hari sebelum tanggal pengambilan.') }}
            </p>
        </div>

        <!-- Section 2 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">2</span>
                {{ __('Pembayaran & PIN Keamanan') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Pola transaksi aman kami menggunakan payment gateway Midtrans. Pelanggan diwajibkan membuat 6 digit PIN Keamanan guna memvalidasi setiap transaksi dompet digital di aplikasi.') }}
            </p>
        </div>

        <!-- Section 3 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">3</span>
                {{ __('Pengambilan & Delivery') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Untuk metode Ambil di Toko (Pick Up), pelanggan wajib menunjukkan Tiket Antrean (Queue Ticket) resmi dari aplikasi kepada staf toko kami. Untuk metode Pengiriman (Delivery), ongkos kirim dihitung berdasarkan jarak koordinat alamat ke toko terdekat.') }}
            </p>
        </div>

        <!-- Section 4 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">4</span>
                {{ __('Alergen & Bahan Makanan') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Produk kami mengandung produk olahan susu, gluten, telur, dan kacang-kacangan. Pelanggan bertanggung jawab penuh untuk memastikan kesesuaian konsumsi keluarga terkait riwayat alergi makanan.') }}
            </p>
        </div>

        <!-- Section 5 -->
        <div class="space-y-2">
            <h2 class="text-sm font-bold text-[#5c4033] flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-[#eedcd3] text-xs font-bold">5</span>
                {{ __('Kebijakan Pembatalan') }}
            </h2>
            <p class="text-xs text-gray-600 leading-relaxed pl-7">
                {{ __('Karena sifat produk makanan segar yang mudah rusak, semua pesanan yang telah dikonfirmasi dan dibayar bersifat final dan tidak dapat dibatalkan atau di-refund.') }}
            </p>
        </div>
    </div>

</div>

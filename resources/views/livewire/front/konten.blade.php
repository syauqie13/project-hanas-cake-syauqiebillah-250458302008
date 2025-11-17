<div>
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>HANA'S CAKE</h1>
            <p>Kue premium dengan sentuhan modern dan cita rasa yang tak terlupakan untuk setiap momen spesial Anda</p>
            <a href="{{ route('ecommerce') }}" class="btn">PESAN SEKARANG</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products">
        <h2 class="section-title">Produk Terlaris Bulan Ini</h2>
        {{-- Menggunakan struktur 'product-scroll-container' baru Anda --}}
        <div class="product-scroll-container">
            <div class="product-grid">

                {{--
                Loop dinamis untuk produk terlaris.
                Struktur ini sekarang menggantikan 6 card statis Anda.
                --}}
                @forelse ($products as $topProduct)
                    @if($topProduct->product)
                        <div class="product-card">
                            <div class="product-img">
                                {{-- Logika gambar dinamis dari database --}}
                                <img src="{{ $topProduct->product && $topProduct->product->image ? asset('storage/' . $topProduct->product->image) : asset('assets/img/news/img08.jpg') }}"
                                    alt="{{ $topProduct->product->name }}"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                            </div>
                            <div class="product-info">
                                {{-- Nama produk dinamis --}}
                                <h3>{{ $topProduct->product->name }}</h3>

                                {{-- Deskripsi dinamis --}}

                                {{-- Jumlah terjual dinamis dari $topProduct->total_qty --}}
                                <span class="sold-count">Terjual {{ $topProduct->total_qty }} bulan ini</span>

                                {{-- Harga dinamis --}}
                                <span class="price">Rp {{ number_format($topProduct->product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                @empty
                    {{-- Tampilan jika tidak ada produk terlaris --}}
                    <div style="text-align: center; grid-column: 1 / -1; padding: 40px 0;">
                        <p style="font-size: 1.2rem; color: #555;">Belum ada produk terlaris untuk bulan ini.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>
</div>

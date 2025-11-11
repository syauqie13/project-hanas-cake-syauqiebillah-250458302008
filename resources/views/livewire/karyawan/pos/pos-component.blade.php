@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        .product-card.disabled {
            background-color: #f1f5f9;
            /* bg-gray-100 */
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            /* Mencegah klik */
        }

        .product-card.disabled .product-name {
            color: #64748b;
            /* text-gray-500 */
        }
    </style>
@endpush

<div>
    <div>
        {{--
        TAMBAHKAN KODE INI DI BAGIAN ATAS FILE BLADE ANDA
        (tepat di bawah <div> pembuka utama)
            --}}

            @if (session()->has('success'))
                <div class_name="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400"
                    role="alert">
                    <span class="font-medium">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            @if (session()->has('info'))
                <div class="p-4 mb-4 text-sm text-blue-800 bg-blue-100 rounded-lg dark:bg-gray-800 dark:text-blue-400"
                    role="alert">
                    <span class="font-medium">Info:</span> {{ session('info') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            {{-- ... Sisa konten blade Anda (Keranjang, Pelanggan, dll) ... --}}

        </div>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header fade-in">
                <h1>
                    <i class="fas fa-cash-register"></i>
                    Kasir
                </h1>
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="row">
                <!-- Products Section -->
                <div class="mb-4 col-lg-7">
                    <div class="card-modern fade-in">
                        <div class="card-header-modern">
                            <h4>
                                <i class="fas fa-box me-2"></i>
                                Daftar Produk
                            </h4>
                        </div>
                        <div class="card-body-modern">
                            <!-- Search & Filter -->
                            <div class="search-filter-group">
                                <div class="search-input">
                                    <div class="input-group">
                                        <span class="bg-white input-group-text border-end-0"
                                            style="border-radius: 10px 0 0 10px; border: 2px solid var(--border-color); border-right: none;">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input wire:model.live.debounce.300ms="search" type="text"
                                            class="form-control-modern border-start-0"
                                            style="border-left: none; border-radius: 0 10px 10px 0;"
                                            placeholder="Cari produk...">
                                    </div>
                                </div>
                                <div style="min-width: 200px;">
                                    <select wire:model.live="selectedCategory" class="form-control-modern">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div class="products-grid">
                                @forelse ($products as $product)
                                                            <div wire:key="product-{{ $product->id }}">

                                                                <div class="product-card {{ $product->stock <= 0 ? 'disabled' : '' }}"
                                                                    title="{{ $product->stock <= 0 ? 'Stok Habis' : 'Klik untuk menambah' }}"
                                                                    @if($product->stock > 0) wire:click="addToCart({{ $product->id }})" @endif>

                                                                    <img src="{{ $product->image
                                    ? asset('storage/' . $product->image)
                                    : 'https://placehold.co/150x150/e2e8f0/cbd5e0?text=Produk'
                                                }}" class="product-image" alt="{{ $product->name }}">

                                                                    <div class="product-info">

                                                                        <div class="product-name" title="{{ $product->name }}">
                                                                            {{ $product->name }}
                                                                        </div>

                                                                        <div class="product-stock">
                                                                            <span class="
                                                        badge-stock
                                                        {{ $product->stock <= 0 ? 'empty' : ($product->stock < 5 ? 'low' : '') }}
                                                    ">
                                                                                <i class="fas fa-box me-1"></i>
                                                                                {{ $product->stock }}
                                                                            </span>
                                                                        </div>

                                                                        <div class="product-price">
                                                                            Rp
                                                                            {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}
                                                                        </div>

                                                                        @if ($product->discount > 0)
                                                                            <div class="product-old-price">
                                                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                                                            </div>
                                                                        @endif

                                                                    </div>

                                                                </div>

                                                            </div>
                                @empty
                                    <div class="empty-state" style="grid-column: 1 / -1;">
                                        <i class="fas fa-box-open"></i>
                                        <div>Produk tidak ditemukan</div>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="mb-4 col-lg-5">
                    <div class="card-modern cart-container fade-in">
                        <div class="card-header-modern">
                            <h4>
                                <i class="fas fa-shopping-cart me-2"></i>
                                Keranjang Belanja
                            </h4>
                        </div>
                        <div class="card-body-modern">
                            <!-- Customer Selection -->
                            <div class="customer-search-wrapper">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-2"></i>Pelanggan
                                </label>
                                @if($selectedCustomerId)
                                    <div class="input-group" wire:key="customer-selected-block">
                                        <input type="text" class="form-control-modern" value="{{ $selectedCustomerName }}"
                                            readonly>
                                        <button class="btn btn-danger-modern" wire:click="clearCustomer" type="button"
                                            style="border-radius: 0 10px 10px 0;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <div wire:key="customer-search-block">
                                        <div class="input-group">
                                            <span class="bg-white input-group-text border-end-0"
                                                style="border-radius: 10px 0 0 10px; border: 2px solid var(--border-color); border-right: none;">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control-modern border-start-0 border-end-0"
                                                style="border-left: none; border-right: none; border-radius: 0;"
                                                placeholder="Cari nama atau no. HP..."
                                                wire:model.live.debounce.300ms="customerSearch"
                                                wire:keydown.escape="clearCustomer">
                                            <button type="button" class="btn btn-success-modern"
                                                style="border-radius: 0 10px 10px 0;" wire:click="create">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        @if(count($customerResults) > 0)
                                            <div class="customer-results">
                                                @foreach($customerResults as $customer)
                                                    <div class="customer-item" wire:click="selectCustomer({{ $customer->id }})"
                                                        wire:key="customer-result-{{ $customer->id }}">
                                                        <strong>{{ $customer->name }}</strong>
                                                        <div class="text-muted small">{{ $customer->phone }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Cart Items -->
                            <div class="cart-items">
                                @forelse ($cart as $productId => $item)
                                    <div class="cart-item" wire:key="cart-item-{{ $productId }}">
                                        <div class="cart-item-info">
                                            <div class="cart-item-name">{{ $item['name'] }}</div>
                                            <div class="cart-item-price">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="cart-item-controls">
                                            <input type="number" wire:model.live="cart.{{ $productId }}.jumlah"
                                                wire:change="updateCartQuantity({{ $productId }}, $event.target.value)"
                                                class="quantity-input" min="1" max="{{ $item['stock'] }}">
                                            <button type="button" wire:click="removeFromCart({{ $productId }})"
                                                class="btn-remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-cart"></i>
                                        <div>Keranjang masih kosong</div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Total Section -->
                            <div class="total-section">
                                <div class="total-row">
                                    <span class="total-label">Total Belanja</span>
                                    <span class="total-amount">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <form wire:submit.prevent="processPayment">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Metode Pembayaran
                                    </label>
                                    <select wire:model.live="payment_method" class="form-control-modern">
                                        <option value="tunai">💵 Tunai</option>
                                        <option value="digital">💳 Bayar Digital</option>
                                    </select>
                                </div>

                                @if ($payment_method == 'tunai')
                                    <div wire:key="payment-tunai-section">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Jumlah Bayar</label>
                                            <div class="input-group">
                                                <span class="bg-white input-group-text"
                                                    style="border-radius: 10px 0 0 10px; border: 2px solid var(--border-color); border-right: none;">
                                                    Rp
                                                </span>
                                                <input wire:model.live.debounce.300ms="paid_amount" type="number"
                                                    class="form-control-modern border-start-0"
                                                    style="border-left: none; border-radius: 0 10px 10px 0;"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                        <div class="mb-3 total-section">
                                            <div class="total-row">
                                                <span class="total-label">Kembalian</span>
                                                <span class="change-amount">
                                                    Rp {{ number_format($change_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="gap-2 d-flex">
                                    <button type="submit" class="btn btn-primary-modern flex-grow-1">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Proses Pembayaran
                                    </button>
                                    <button type="button" wire:click="clearCart" class="btn btn-danger-modern">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <iframe id="receipt-printer"
            style="position: absolute; left: -9999px; top: -9999px; width: 302px; height: 100px; border: 0;"></iframe>
        <!-- Modal Component -->
        <livewire:karyawan.pos.create-customer-modal />

        <!-- Script tambahan (optional, jika ingin alert dll) -->
        @push('js')
            <!-- 1. Muat skrip Snap (Sama seperti contoh Anda) -->
            <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ config('services.midtrans.client_key') }}">
                </script>

            <script>
                document.addEventListener('livewire:init', () => {

                    Livewire.on('snap-show', (data) => {

                        if (data.snapToken) {
                            console.log('Snap token diterima, membuka popup...');

                            // 3. INI ADALAH ADAPTASI DARI 'snap.pay(<?$snapToken?>, ...)'
                            // Kita menggunakan token dari 'data' event
                            window.snap.pay(data.snapToken, {

                                // 4. INI ADALAH ADAPTASI DARI 'onSuccess' ANDA
                                // Alih-alih 'result-json', kita melakukan redirect
                                onSuccess: function (result) {
                                    console.log('Pembayaran Sukses:', result);
                                    // Ini adalah alur "Gaya B" (Redirect)
                                    // Kita redirect ke halaman validasi menggunakan URL Relatif
                                    window.location.href = `/karyawan/validasi/${result.order_id}`;
                                },

                                // 5. INI ADALAH ADAPTASI DARI 'onPending' ANDA
                                onPending: function (result) {
                                    console.log('Pembayaran Pending:', result);
                                    // Redirect ke halaman validasi dengan status pending
                                    window.location.href = `/karyawan/validasi/${result.order_id}?status=pending`;
                                },

                                // 6. INI ADALAH ADAPTASI DARI 'onError' ANDA
                                onError: function (result) {
                                    console.log('Pembayaran Error:', result);
                                    // Redirect kembali ke POS dengan pesan gagal
                                    window.location.href = `/karyawan/pos?payment_failed=1`;
                                },

                                // 7. INI ADALAH ADAPTASI DARI 'onClose' (Tambahan)
                                onClose: function () {
                                    console.log('Popup ditutup.');
                                    // Redirect kembali ke POS
                                    window.location.href = `/karyawan/pos`;
                                }
                            });
                        }
                    });

                    // --- Listener 'notify' (Untuk Pembayaran Tunai & Error) ---
                    if (typeof Swal !== 'undefined') {
                        Livewire.on('notify', (data) => {
                            Swal.fire({
                                icon: data.icon || 'success',
                                title: data.title || 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                        });
                    }

                    // =======================================================
                    // INI ADALAH PENANGKAP CETAK STRUK UNTUK TUNAI
                    // (Ini sudah berisi perbaikan untuk 'infinite loop')
                    // =======================================================
                    Livewire.on('print-receipt', (data) => {
                        const orderId = data.orderId;
                        if (!orderId) {
                            console.error('Print receipt dipanggil tanpa orderId.');
                            return;
                        }

                        const iframe = document.getElementById('receipt-printer');
                        if (!iframe) {
                            console.error('Iframe #receipt-printer tidak ditemukan.');
                            return;
                        }

                        const printUrl = `/karyawan/struk/${orderId}`;
                        console.log('Mencetak struk dari:', printUrl);

                        let hasPrinted = false; // Flag untuk mencegah loop

                        // Atur 'onload' HANYA SEKALI
                        iframe.onload = function () {
                            // Cek flag dan pastikan src-nya adalah URL struk (bukan 'about:blank')
                            if (!hasPrinted && iframe.src.includes(printUrl)) {
                                try {
                                    console.log('Iframe dimuat, memanggil print...');
                                    hasPrinted = true; // Set flag

                                    iframe.contentWindow.focus(); // Fokus ke iframe
                                    iframe.contentWindow.print(); // Panggil print
                                } catch (e) {
                                    console.error('Gagal memanggil print:', e);
                                }

                                // Bersihkan iframe setelah print (untuk mencegah print ulang saat refresh)
                                setTimeout(() => {
                                    console.log('Membersihkan iframe...');
                                    iframe.src = 'about:blank';
                                    iframe.onload = null; // Hapus handler onload
                                }, 1000);
                            }
                        };

                        // Atur src untuk memuat struk (ini akan memicu onload)
                        iframe.src = printUrl;
                    });

                });
            </script>
        @endpush

    </div>

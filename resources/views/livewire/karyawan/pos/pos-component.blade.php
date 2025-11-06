@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
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
                    Point of Sale (Kasir)
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
                                        <div wire:click="addToCart({{ $product->id }})" class="product-card">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}"
                                                class="product-image" alt="{{ $product->name }}">
                                            <div class="product-info">
                                                <div class="product-name" title="{{ $product->name }}">
                                                    {{ $product->name }}
                                                </div>
                                                <div class="product-stock">
                                                    <span
                                                        class="badge-stock {{ $product->stock < 5 ? 'low' : ($product->stock == 0 ? 'empty' : '') }}">
                                                        <i class="fas fa-box me-1"></i>{{ $product->stock }}
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
                                    <div class="empty-state" style="grid-column: 1/-1;">
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

        <!-- Modal Component -->
        <livewire:karyawan.pos.create-customer-modal />

        <!-- Script tambahan (optional, jika ingin alert dll) -->
        @push('js')
            <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ config('services.midtrans.client_key') }}">
                </script>

            <script>
                // Listener untuk notifikasi (Swal)
                window.addEventListener('notify', event => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: event.detail.message || 'Aksi berhasil dijalankan!',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                });

                // Listener untuk Livewire (termasuk Snap)
                document.addEventListener('livewire:initialized', () => {

                    // Gunakan sintaks JavaScript murni. Fungsinya 100% SAMA.
                    Livewire.on('snap-show', (event) => {

                        window.snap.pay(event.snapToken, {
                            // Callback saat pembayaran sukses (di sisi KLIEN)
                            onSuccess: function (result) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Silakan tunggu konfirmasi.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                            },
                            // Callback saat pembayaran pending
                            onPending: function (result) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Selesaikan pembayaran Anda.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                });
                            },
                            // Callback saat terjadi error
                            onError: function (result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Silakan coba lagi.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                });
                            },
                            // Callback saat popup ditutup
                            onClose: function () {
                                /* Pengguna menutup popup tanpa menyelesaikan */
                            }
                        });

                    });

                    // Anda bisa menambahkan listener @this.on() lainnya di sini
                    // jika ada

                });
            </script>
        @endpush
    </div>

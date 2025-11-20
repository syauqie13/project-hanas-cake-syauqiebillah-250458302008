@push('styles')
        <link rel="stylesheet" href="{{ asset('css-app.css') }}">
<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

/* Style kustom Anda dari template */
table.table tbody tr:hover {
    background: #f7f9fc !important;
    transition: 0.2s;
}
.filter-bar {
    padding: 1.2rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e4e6ef;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 12px;
}
.card-header {
    padding: 15px 20px !important;
}
.card-header h4 {
    font-weight: 600;
    font-size: 1.05rem;
}
.card-header-form .form-control {
    height: 42px !important;
    border-radius: 6px;
    padding-left: 12px;
}
.badge {
    padding: 6px 10px;
    font-size: 0.75rem;
    border-radius: 4px;
    font-weight: 600;
}
.dropdown-menu {
    border-radius: 6px;
    font-size: 0.85rem;
}
table.table td {
    vertical-align: middle !important;
    font-size: 0.92rem;
}
.card-icon.bg-total {
    background-color: #6777ef;
    color: #fff;
}

</style>

@endpush

<div wire:poll.5s>
<!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center"><div>
                <h1>📦 Manajemen Pesanan (PO)</h1>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Menunggu Bayar</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['pending'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Perlu Dibuat</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['processing'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Selesai</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['completed'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Di Antar</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['shipped'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-total"> {{-- bg-secondary diganti bg-total --}}
                        <i class="fas fa-dollar-sign"></i> {{-- Icon diganti --}}
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pendapatan (Lunas)</h4> {{-- Judul diganti --}}
                        </div>
                        <div class="card-body">
                           Rp {{ number_format($totalPendapatan, 0, ',', '.') }} {{-- Variabel diganti --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="shadow-sm card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <h4 class="mb-3 mb-md-0">
                        <i class="mr-2 fas fa-clipboard-list text-primary"></i>
                        Daftar Transaksi Online
                    </h4>

                     <div class="card-header-form">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                placeholder="Cari ID Order, Pelanggan, Kasir...">
                    </div>
                </div>

                {{-- SEARCH (Struktur Asli Anda) --}}
                 <div class="p-3 border-bottom bg-offline rounded-top">
                        <div class="row align-items-end">

                            <div class="mb-3 col-md-3">
                                <label class="font-weight-bold"><i class="mr-1 fas fa-filter"></i> Status</label>
                                <select wire:model.live="filterStatus" class="shadow-sm form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Diproses</option>
                                    <option value="completed">Selesai</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="font-weight-bold"><i class="mr-1 fas fa-calendar"></i> Tanggal</label>
                                <select wire:model.live="filterTanggal" class="shadow-sm form-control">
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="month">Bulan Ini</option>
                                    <option value="all">Semua</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="font-weight-bold"><i class="mr-1 fas fa-credit-card"></i> Metode
                                    Bayar</label>
                                <select wire:model.live="filterPaymentMethod" class="shadow-sm form-control">
                                    <option value="all">Semua Metode</option>
                                    <option value="tunai">Tunai</option>

                                    <option value="midtrans">Midtrans / Digital</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="font-weight-bold"><i class="mr-1 fas fa-download"></i> Export</label>
                                <button wire:click="export" wire:loading.attr="disabled"
                                    class="shadow-sm btn btn-success btn-block">
                                    <span wire:loading.remove wire:target="export">
                                        <i class="mr-2 fas fa-file-excel"></i> Export Excel
                                    </span>
                                    <span wire:loading wire:target="export">
                                        <i class="mr-2 fas fa-spinner fa-spin"></i> Exporting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
            {{-- FILTER BAR (Dihapus karena sudah digabung di card-header) --}}

                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="align-middle">ID Order</th>
                                <th scope="col" class="align-middle">Pelanggan</th>
                                <th scope="col" class="text-center align-middle">Total</th>
                                <th scope="col" class="text-center align-middle">Pengiriman</th>
                                <th scope="col" class="text-center align-middle">Status Bayar</th>
                                <th scope="col" class="text-center align-middle">Status Order</th>
                                <th scope="col" class="text-center align-middle">Ubah Status</th>
                                <th scope="col" class="text-center align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($orders as $order)
                            <tr class="hover-highlight" wire:key="order-{{ $order->id }}">

                                {{-- Kolom Order ID & Tanggal --}}
                                <td class="align-middle font-weight-600 text-dark">
                                    #{{ $order->merchant_order_id }}
                                    <div class="text-small text-muted">
                                        {{ $order->tanggal->format('d M Y, H:i') }}
                                    </div>
                                </td>

                                {{-- Kolom Nama Pemesan --}}
                                <td class="align-middle">
                                    {{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}
                                </td>

                                {{-- Total Harga --}}
                                <td class="text-center align-middle">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                {{-- Tipe Order --}}
                                <td class="text-center align-middle">
                                    @if($order->order_type == 'pos')
                                        <span class="badge badge-light"><i class="mr-1 fas fa-store"></i> Di Toko</span>
                                    @elseif($order->order_type == 'online')
                                        @if($order->shipping_zone_name == 'Ambil di Toko (Pickup)')
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="mb-1 badge badge-success">
                                                    <i class="mr-1 fas fa-walking"></i> Pickup
                                                </span>
                                            </div>
                                        @elseif($order->shipping_zone_name)
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="mb-1 badge badge-info">
                                                    <i class="mr-1 fas fa-truck"></i> Delivery
                                                </span>
                                                <small class="text-muted font-weight-bold" style="font-size: 10px;">
                                                    {{ $order->shipping_zone_name }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="badge badge-warning">Cek Detail?</span>
                                        @endif
                                    @endif
                                </td>

                                {{-- Status Pembayaran --}}
                                <td class="text-center align-middle">
                                    @if($order->payment_status == 'paid')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Gagal</span>
                                    @endif
                                </td>

                                {{-- Status Order --}}
                                <td class="text-center align-middle">
                                    @switch($order->status)
                                        @case('selesai')
                                            <span class="badge badge-light">Selesai</span>
                                            @break
                                        @case('dikirim')
                                            <span class="badge badge-info">Siap Diambil / Dikirim</span>
                                            @break
                                        @case('diproses')
                                            <span class="badge badge-primary">Diproses</span>
                                            @break
                                        @case('dibatalkan')
                                            <span class="badge badge-danger">Dibatalkan</span>
                                            @break
                                        @default
                                            <span class="badge badge-warning">{{ $order->status }}</span>
                                    @endswitch
                                </td>

                                {{-- Ubah Status (Dropdown) --}}
                                <td class="text-center align-middle">
                                    @if($order->payment_status == 'paid')
                                        <div class="dropdown">
                                            <button class="border btn btn-sm btn-light dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton{{ $order->id }}"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                    {{ in_array($order->status, ['selesai', 'dibatalkan']) ? 'disabled' : '' }}>
                                                <i class="fas fa-cog"></i> Ubah
                                            </button>

                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                aria-labelledby="dropdownMenuButton{{ $order->id }}">
                                                @foreach($statusOptions as $key => $value)
                                                    <button type="button"
                                                            class="cursor-pointer dropdown-item d-flex align-items-center"
                                                            wire:click="setStatus({{ $order->id }}, '{{ $key }}')">
                                                        {{ $value }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Tombol Detail --}}
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-info"
                                            wire:click="showDetailModal({{ $order->id }})">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-muted">
                                    <i class="mb-3 fas fa-box-open fa-3x"></i>
                                    <div class="mb-0 h5">Belum ada pesanan online (PO).</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
            <div class="text-right card-footer">
                <nav class="d-inline-block">
                    {{ $orders->links('livewire::bootstrap') }}
                </nav>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail -->
 @if($detailModalOpen && $selectedOrder)

    <div style="display: block;">

        <div class="modal fade show" id="detailOrderModal" tabindex="-1" role="dialog" style="display: block;">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">📋 Detail Pesanan: <strong>#{{ $selectedOrder->merchant_order_id }}</strong></h5>

                        <button type="button" class="close" wire:click="closeDetailModal" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <div class="modal-body">



                        <div class="row">

                            <!-- Kolom Kiri: Info Pelanggan & Pengiriman -->

                            <div class="col-12 col-md-6">

                                <h6>👤 Pelanggan & Pengiriman</h6>

                                <ul class="list-group list-group-flush">

                                    <li class="px-0 list-group-item">

                                        <strong>Nama:</strong> {{ $selectedOrder->shipping_name ?? $selectedOrder->user->name ?? 'N/A' }}

                                    </li>

                                    <li class="px-0 list-group-item">

                                        <strong>Email:</strong> {{ $selectedOrder->shipping_email ?? $selectedOrder->user->email ?? 'N/A' }}

                                    </li>

                                    <li class="px-0 list-group-item">

                                        <strong>Telepon:</strong> {{ $selectedOrder->shipping_phone ?? $selectedOrder->user->phone ?? 'N/A' }}

                                    </li>

                                    <li class="px-0 list-group-item">

                                        <strong>Alamat:</strong>

                                        <p class="mb-0">{{ $selectedOrder->shipping_address ?? 'N/A' }}</p>

                                        <p class="mb-0">{{ $selectedOrder->shipping_city ?? '' }}, {{ $selectedOrder->shipping_postal_code ?? '' }}</p>

                                    </li>

                                </ul>

                            </div>



                            <!-- Kolom Kanan: Detail Item Pesanan -->

                            <div class="col-12 col-md-6">

                                <h6>🛒 Item Pesanan</h6>

                                <div class="table-responsive">

                                    <table class="table table-sm table-striped">

                                        <thead>

                                            <tr>

                                                <th>Produk</th>

                                                <th class="text-center">Jml</th>

                                                <th class="text-right">Subtotal</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($selectedOrder->items as $item)

                                            <tr>

                                                <td class="font-weight-600">{{ $item->product->name ?? 'Produk Dihapus' }}</td>

                                                <td class="text-center">{{ $item->jumlah }}</td>

                                                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>

                                            </tr>

                                            @endforeach

                                        </tbody>



                                        <tfoot>

                                            <tr>

                                                <td colspan="2" class="text-right small text-muted">Subtotal Produk</td>

                                                <td class="text-right small">

                                                    Rp {{ number_format($selectedOrder->total - $selectedOrder->shipping_price, 0, ',', '.') }}

                                                </td>

                                            </tr>



                                            <tr>

                                                <td colspan="2" class="text-right small text-muted">

                                                    Ongkir

                                                    @if($selectedOrder->shipping_zone_name)

                                                        ({{ $selectedOrder->shipping_zone_name }})

                                                    @endif

                                                </td>
                                                <td class="text-right small text-primary font-weight-bold">
                                                    @if($selectedOrder->shipping_price > 0)
                                                        + Rp {{ number_format($selectedOrder->shipping_price, 0, ',', '.') }}
                                                    @else
                                                        Gratis
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr class="border-top">
                                                <td colspan="2" class="text-right font-weight-bold">TOTAL BAYAR</td>
                                                <td class="text-right font-weight-bold text-dark" style="font-size: 1.1em;">
                                                    Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->

        <div class="modal-backdrop fade show"></div>

    </div>

    @endif

@push('js')
    <!-- Custom JS if needed -->
@endpush


</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css-app.css') }}">
@endpush

<div wire:poll.15s>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen Transaksi POS</h1>
            </div>

            <div class="section-body">

                {{-- --- KARTU STATISTIK --- --}}
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-offline">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pendapatan Tunai</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats->total_pendapatan_offline ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-total">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pendapatan Digital</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats->total_pendapatan_online ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-total-pesanan">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pesanan</h4>
                                </div>
                                <div class="card-body">
                                    {{ $stats->total_pesanan ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-secondary">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Selesai</h4>
                                </div>
                                <div class="card-body">
                                    {{ $stats->total_selesai ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- --- AKHIR KARTU STATISTIK --- --}}

                <div class="shadow-sm card">
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h4 class="mb-3 mb-md-0">
                            <i class="mr-2 fas fa-cash-register text-primary"></i>
                            Daftar Transaksi Offline
                        </h4>

                        {{-- SEARCH — tetap original --}}
                        <div class="card-header-form">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                placeholder="Cari ID Order, Pelanggan, Kasir...">
                        </div>
                    </div>

                    {{-- FILTER BAR --}}
                    <div class="p-3 border-bottom bg-light rounded-top">
                        <div class="row align-items-end">

                            <div class="mb-3 col-md-3">
                                <label class="font-weight-bold"><i class="mr-1 fas fa-filter"></i> Status</label>
                                <select wire:model.live="filterStatus" class="shadow-sm form-control">
                                    <option value="all">Semua Status</option>
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

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Order</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Kasir</th>
                                    <th class="text-right">Total</th>

                                    <th class="text-center">Metode Bayar</th>

                                    <th class="text-center">Status Bayar</th>
                                    <th class="text-center">Status Order</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->merchant_order_id }}</strong></td>
                                        <td>{{ $order->tanggal->format('d M Y, H:i') }}</td>
                                        <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                        <td>{{ $order->cashier->name ?? 'N/A' }}</td>
                                        <td class="text-right text-primary font-weight-bold">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            @if($order->payment_method == 'tunai')
                                                <span class="px-3 py-1 badge badge-secondary">💵 Tunai</span>
                                            @else
                                                <span class="px-3 py-1 badge badge-primary">💳 Midtrans</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($order->payment_status == 'paid')
                                                <span class="px-3 py-1 badge badge-success">Lunas</span>
                                            @else
                                                <span class="px-3 py-1 badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge
                                            @if($order->status == 'selesai') badge-light
                                            @elseif($order->status == 'diproses') badge-primary
                                            @else badge-warning @endif px-3 py-1">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="shadow-sm btn btn-sm btn-info"
                                                wire:click="showDetail({{ $order->id }})">
                                                <i class="mr-1 fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-5 text-center text-muted">
                                            <i class="mb-2 fas fa-exclamation-circle fa-2x"></i>
                                            <p>Tidak ada transaksi POS yang ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="text-right card-footer">
                        <nav class="d-inline-block">
                            {{ $orders->links('livewire::bootstrap') }}
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- MODAL DETAIL TRANSAKSI --}}
    @if($showDetailModal && $selectedOrder)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi POS: #{{ $selectedOrder->merchant_order_id }}</h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Pelanggan:</strong>
                                <p>{{ $selectedOrder->customer->name ?? 'Guest' }}</p>
                                <strong>Kasir:</strong>
                                <p>{{ $selectedOrder->cashier->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right col-md-6">
                                <strong>Tanggal:</strong>
                                <p>{{ $selectedOrder->tanggal->format('d M Y, H:i') }}</p>
                                <strong>Metode Bayar:</strong>
                                <p class="text-uppercase">{{ $selectedOrder->payment_method }}</p>
                            </div>
                        </div>

                        <hr>
                        <h6>Item Dibeli:</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedOrder->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total</span>
                                        <strong class="text-primary">Rp
                                            {{ number_format($selectedOrder->total, 0, ',', '.') }}</strong>
                                    </li>
                                    @if($selectedOrder->payment_method == 'tunai')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Dibayar (Tunai)</span>
                                            <span>Rp {{ number_format($selectedOrder->paid_amount, 0, ',', '.') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Kembalian</span>
                                            <span>Rp {{ number_format($selectedOrder->change_amount, 0, ',', '.') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        <a href="{{ route('karyawan.struk.print', $selectedOrder->id) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Ulang Struk
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

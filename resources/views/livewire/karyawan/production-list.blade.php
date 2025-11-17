@push('styles')
    <style>
        /* (Style Anda dari file lain) */
        thead tr th { border: none !important; letter-spacing: 0.3px; }
        tbody tr:hover { background-color: #f7f9fc !important; transition: 0.2s ease; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #fcfcfd; }
    </style>
@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Daftar Produksi (PO)</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">Daftar ini menjumlahkan semua produk dari pesanan PO yang berstatus "Sedang Diproses".</div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title"><i class="mr-2 fas fa-fire-alt"></i> Total Produk (Perlu Dibuat)</h4>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead class="text-white">
                                            <tr>
                                                <th scope="col" class="align-middle">Nama Produk</th>
                                                <th scope="col" class="text-center align-middle">Total Kuantitas</th>
                                                <th scope="col" class="text-center align-middle">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($processingList as $item)
                                                <tr class="hover-highlight">
                                                    <td class="align-middle font-weight-600 text-dark">
                                                        {{ $item->product_name }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-primary" style="font-size: 1rem;">
                                                            {{ $item->total_quantity_needed }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <a href="{{ route('karyawan.list-product') }}" wire:navigate class="btn btn-sm btn-outline-secondary">
                                                            Lihat Produk
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="py-5 text-center text-muted">
                                                        <i class="mb-3 fas fa-check-circle fa-2x"></i>
                                                        <div class="mb-0 h6">Tidak ada produk yang perlu dibuat saat ini.</div>
                                                        <small>Semua pesanan lunas sudah "Selesai" atau "Dibatalkan".</small>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Instruksi</h4>
                            </div>
                            <div class="card-body">
                                <p>Halaman ini berfungsi sebagai **Daftar Kerja Dapur**.</p>
                                <p>Angka di tabel adalah total jumlah produk yang perlu Anda buat dari semua pesanan PO yang statusnya **"Sedang Diproses"**.</p>
                                <hr>
                                <p class="mb-0">Setelah produk selesai dibuat dan dikirim, ubah status pesanannya menjadi **"Siap diambil / dikirim"** di halaman <a href="{{ route('karyawan.orders.list') }}">Manajemen Pesanan</a>. Produk tersebut akan otomatis hilang dari daftar ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

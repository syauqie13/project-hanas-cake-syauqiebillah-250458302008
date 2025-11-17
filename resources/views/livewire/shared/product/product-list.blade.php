@push('styles')

@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Data Produk</h1>
                </div>

                @auth
                    @if (Auth::user()->role === 'karyawan')
                        <!-- Tombol Tambah Produk (Sekarang memanggil event) -->
                        <button wire:click="$dispatch('openCreateModal')"
                            class="shadow-sm btn btn-primary d-flex align-items-center">
                            <i class="mr-2 fas fa-box"></i> Tambah Produk
                        </button>
                    @endif
                @endauth

            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Produk</h4>
                                <div class="card-header-form">
                                    <div class="input-group">
                                        <button wire:click="export" wire:loading.attr="disabled"
                                            class="shadow-sm btn btn-success d-flex align-items-center">
                                            <span wire:loading.remove wire:target="export">
                                                <i class="mr-2 fas fa-file-excel"></i> Export Excel
                                            </span>
                                            <span wire:loading wire:target="export">
                                                <i class="mr-2 fas fa-spinner fa-spin"></i> Exporting...
                                            </span>
                                        </button>
                                        <button wire:click="openProductListImportModal" wire:loading.attr="disabled"
                                            class="shadow-sm btn btn-primary d-flex align-items-center">
                                            <span wire:loading.remove wire:target="export">
                                                <i class="mr-2 fas fa-file-excel"></i> Import Excel
                                            </span>
                                            <span wire:loading wire:target="export">
                                                <i class="mr-2 fas fa-spinner fa-spin"></i> Exporting...
                                            </span>
                                        </button>
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari produk...">

                                    </div>
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead class="text-white">
                                            <tr>
                                                <th scope="col" class="text-center align-middle" style="width: 60px;">No
                                                </th>
                                                <th scope="col" class="align-middle">Nama Produk</th>
                                                <th scope="col" class="text-center align-middle">Status</th>
                                                <th scope="col" class="text-center align-middle">Kategori</th>
                                                <th scope="col" class="text-center align-middle">Harga</th>
                                                <th scope="col" class="text-center align-middle">Stok</th>
                                                <th scope="col" class="text-center align-middle">Diskon</th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($products as $index => $product)
                                                <tr class="hover-highlight">
                                                    <td class="text-center align-middle text-muted font-weight-bold">
                                                        {{ $products->firstItem() + $index }}
                                                    </td>
                                                    <td class="align-middle font-weight-600 text-dark">
                                                        {{ $product->name }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{--
                                                        Cek 1: Apakah ini produk PO?
                                                        Cek 2: Apakah deadline-nya ada?
                                                        Cek 3: Apakah deadline-nya MASIH di masa depan?
                                                        --}}
                                                        @if($product->is_po && $product->po_deadline && $product->po_deadline->isFuture())
                                                            <!-- Ini adalah PO yang sedang aktif -->
                                                            <span class="px-3 py-2 shadow-sm badge badge-primary"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="PO Aktif (Deadline: {{ $product->po_deadline->format('d M Y') }})">
                                                                <i class="mr-1 fas fa-clock"></i> PO Aktif
                                                            </span>

                                                        @else
                                                            <!-- Ini adalah produk ready, atau PO yang sudah selesai -->
                                                            <span class="px-3 py-2 shadow-sm badge badge-success">
                                                                <i class="mr-1 fas fa-check"></i> Ready
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <span class="px-3 py-2 shadow-sm badge badge-info">
                                                            {{ $product->category->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $product->stock }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $product->discount === null ? '0.00' : $product->discount }}%
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <div class="dropdown">
                                                            <button class="border btn btn-sm btn-light dropdown-toggle"
                                                                type="button" id="dropdownMenuButton{{ $product->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border-radius: 10px;">
                                                                <i class="fas fa-ellipsis-v text-secondary"></i>
                                                            </button>
                                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                                aria-labelledby="dropdownMenuButton{{ $product->id }}">

                                                                <!-- TOMBOL BARU: Atur Resep -->
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="$dispatch('openRecipeModal', { id: {{ $product->id }} })">
                                                                    <i class="mr-2 fas fa-book text-primary"></i> Atur Resep
                                                                </button>


                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="$dispatch('openEditModal', { id: {{ $product->id }} })">
                                                                    <i class="mr-2 fas fa-edit text-warning"></i> Edit
                                                                </button>
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center text-danger"
                                                                    wire:click="deleteConfirm({{ $product->id }})">
                                                                    <i class="mr-2 fas fa-trash-alt"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="py-5 text-center text-muted">
                                                        <i class="mb-3 fas fa-box-open fa-2x"></i>
                                                        <div class="mb-0 h6">Belum ada data produk</div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right card-footer">
                                <nav class="d-inline-block">
                                    {{ $products->links('livewire::bootstrap') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- MODAL IMPORT --}}
        @if($showProductListImportModal)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5); z-index: 1050;"
                aria-modal="true">

                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="rounded-lg shadow-lg modal-content">

                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark">
                                <i class="mr-2 fas fa-file-excel text-success"></i> Upload File Excel
                            </h5>
                            <button type="button" class="close" wire:click="closeProductListImportModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="p-4 modal-body">
                            <div class="mb-0 form-group">
                                <div class="p-4 text-center rounded position-relative d-flex justify-content-center align-items-center flex-column"
                                    style="border: 2px dashed #cdd3d8; background-color: #f8f9fa; transition: all 0.3s;">

                                    <i class="mb-3 fas fa-cloud-upload-alt fa-3x text-secondary"></i>

                                    <div class="text-center custom-file">
                                        <label class="mb-2 d-block text-muted" style="font-size: 0.9rem;">
                                            Klik tombol di bawah atau drag file ke sini
                                        </label>
                                        <input type="file" wire:model="fileImport"
                                            class="pl-0 text-center border-0 form-control-file"
                                            style="width: auto; display: inline-block;">
                                    </div>
                                </div>

                                <div wire:loading wire:target="fileImport" class="mt-2 text-center text-primary small">
                                    <i class="fas fa-spinner fa-spin"></i> Sedang membaca file...
                                </div>

                                @error('fileImport')
                                    <div class="mt-2 text-center text-danger small font-weight-bold">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror

                                <div class="px-3 py-2 mt-3 mb-0 alert alert-info" style="font-size: 0.85rem;">
                                    <i class="mr-1 fas fa-info-circle"></i>
                                    <strong>Format Header Wajib:</strong><br>
                                    nama_kue, harga, stok, diskon
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" wire:click="closeProductListImportModal">
                                Batal
                            </button>
                            <button type="button" class="shadow-sm btn btn-primary" wire:click="import"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="import">
                                    <i class="mr-1 fas fa-upload"></i> Upload Sekarang
                                </span>
                                <span wire:loading wire:target="import">
                                    <i class="mr-1 fas fa-spinner fa-spin"></i> Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Create & Edit Produk (Komponen Anda yang sudah ada) -->
    <livewire:shared.product.product-create />
    <livewire:shared.product.product-edit />

    <!-- MODAL BARU UNTUK MENGATUR RESEP -->
    <livewire:shared.product.product-recipes />

    @push('js')
        <!-- Notifikasi sukses -->
        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail.icon || 'success',
                    title: event.detail.icon === 'error' ? 'Gagal!' : 'Berhasil!',
                    text: event.detail.message || 'Aksi berhasil dijalankan!',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        </script>

        <!-- Konfirmasi hapus -->
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirmDelete', (data) => {
                    // Ambil ID dari data event
                    const id = data.id || (data[0] ? data[0].id : null);

                    if (!id) {
                        console.error('ID tidak ditemukan untuk dihapus');
                        return;
                    }

                    Swal.fire({
                        title: 'Yakin hapus produk ini?',
                        // Pesan khusus untuk produk
                        text: "Data produk, gambar, DAN SEMUA RESEPNYA akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim balik ke listener #[On('deleteConfirmed')]
                            Livewire.dispatch('deleteConfirmed', { id: id });
                        }
                    });
                });
            });
        </script>

        <script>
            // Ganti listener 'livewire:navigated' Anda dengan yang ini
            document.addEventListener('livewire:navigated', () => {

                // 1. Re-init Dropdowns (Memperbaiki tombol dropdown)
                // Kita 'dispose' dulu untuk membuang yg lama, lalu init yg baru
                if ($('[data-toggle="dropdown"]').length) {
                    $('[data-toggle="dropdown"]').dropdown('dispose');
                    $('[data-toggle="dropdown"]').dropdown();
                }

                // 2. Re-init Tooltips (PENTING: Anda punya ini di halaman produk)
                // Ini agar 'title' di badge PO Aktif bisa muncul saat di-hover
                if ($('[data-toggle="tooltip"]').length) {
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    $('[data-toggle="tooltip"]').tooltip();
                }

                // 3. Re-init Popovers (Jaga-jaga jika Anda pakai)
                if ($('[data-toggle="popover"]').length) {
                    $('[data-toggle="popover"]').popover('dispose');
                    $('[data-toggle="popover"]').popover();
                }

                // 4. Re-init Custom Scrollbar (Nicescroll)
                // Ini penting agar sidebar tetap bisa di-scroll
                if (jQuery().nicescroll) {
                    // Stisla menggunakan class ini untuk sidebar-nya
                    $(".main-sidebar").getNiceScroll().resize();
                }
            });
        </script>
    @endpush
</div>

@push('styles')
    <style>
        .avatar img {
            object-fit: cover;
        }

        .dropdown-menu {
            min-width: 130px;
            font-size: 0.9rem;
        }
    </style>

    <style>
        thead tr th {
            border: none !important;
            letter-spacing: 0.3px;
        }

        tbody tr:hover {
            background-color: #f7f9fc !important;
            transition: 0.2s ease;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfd;
        }
    </style>
@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Manajemen Inventaris</h1>
                </div>

                <button wire:click="create" class="shadow-sm btn btn-primary d-flex align-items-center">
                    <i class="mr-2 fas fa-plus-circle"></i> Tambah Item Inventaris
                </button>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Item Inventaris</h4>
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
                                        <button wire:click="openImportModal" wire:loading.attr="disabled"
                                            class="shadow-sm btn btn-primary d-flex align-items-center">
                                            <span wire:loading.remove wire:target="export">
                                                <i class="mr-2 fas fa-file-excel"></i> Import Excel
                                            </span>
                                            <span wire:loading wire:target="export">
                                                <i class="mr-2 fas fa-spinner fa-spin"></i> Exporting...
                                            </span>
                                        </button>
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari item (Tepung, Gula...)">

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
                                                <th scope="col" class="align-middle">Nama Item</th>
                                                <th scope="col" class="text-center align-middle">Tipe</th>
                                                <th scope="col" class="text-center align-middle">Stok</th>
                                                <th scope="col" class="text-center align-middle">Satuan</th>
                                                <th scope="col" class="text-center align-middle">Harga Satuan (Modal)
                                                </th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($inventories as $index => $item)
                                                <tr class="hover-highlight">
                                                    <td class="text-center align-middle text-muted font-weight-bold">
                                                        {{ $inventories->firstItem() + $index }}
                                                    </td>
                                                    <td class="align-middle font-weight-600 text-dark">
                                                        {{ $item->name }}
                                                        @if($item->description)
                                                            <div class="text-xs text-muted">
                                                                {{ Str::limit($item->description, 50) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span
                                                            class="badge {{ $item->type == 'bahan_baku' ? 'badge-warning' : 'badge-success' }}"
                                                            style="text-transform: capitalize;">
                                                            {{ str_replace('_', ' ', $item->type) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="px-3 py-2 shadow-sm badge badge-light">
                                                            {{-- Tampilkan sebagai angka bulat (atau 2 desimal jika Anda
                                                            mau) --}}
                                                            {{ number_format($item->stock, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $item->unit }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        Rp {{ number_format($item->unit_price, 2, ',', '.') }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <div class="dropdown">
                                                            <button class="border btn btn-sm btn-light dropdown-toggle"
                                                                type="button" id="dropdownMenuButton{{ $item->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border-radius: 10px;">
                                                                <i class="fas fa-ellipsis-v text-secondary"></i>
                                                            </button>
                                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                                aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="edit({{ $item->id }})">
                                                                    <i class="mr-2 fas fa-edit text-warning"></i> Edit
                                                                </button>
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center text-danger"
                                                                    wire:click="deleteConfirm({{ $item->id }})">
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
                                                        <div class="mb-0 h6">Belum ada data inventaris</div>
                                                        <small>Tambahkan item baru untuk mulai mengelola data.</small>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right card-footer">
                                <nav class="d-inline-block">
                                    {{ $inventories->links('livewire::bootstrap') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- MODAL IMPORT --}}
        @if($showInventoryListImportModal)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5); z-index: 1050;"
                aria-modal="true">

                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="rounded-lg shadow-lg modal-content">

                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark">
                                <i class="mr-2 fas fa-file-excel text-success"></i> Upload File Excel
                            </h5>
                            <button type="button" class="close" wire:click="closeImportModal" aria-label="Close">
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
                            <button type="button" class="btn btn-secondary" wire:click="closeImportModal">
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

    <!-- ====================================== -->
    <!-- MODAL UNTUK BUAT/EDIT ITEM INVENTARIS -->
    <!-- ====================================== -->
    @if($isOpen)
        <div class="" style="display: block;">
            <div class="modal fade show" id="inventoryModal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $inventoryId ? 'Edit Item Inventaris' : 'Tambah Item Inventaris Baru' }}
                            </h5>
                            <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit.prevent="store">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Item</label>
                                    <input type="text" wire:model="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Contoh: Tepung Terigu">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipe Item</label>
                                            <select wire:model="type"
                                                class="form-control @error('type') is-invalid @enderror">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="bahan_baku">Bahan Baku</option>
                                                <option value="produk_jadi">Produk Jadi</option>
                                            </select>
                                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Satuan (Unit)</label>
                                            {{-- PERUBAHAN DI SINI --}}
                                            <select wire:model="unit"
                                                class="form-control @error('unit') is-invalid @enderror">
                                                <option value="">-- Pilih Satuan --</option>
                                                <option value="gram">gram (Gram)</option>
                                                <option value="ml">ml (Mililiter)</option>
                                                <option value="pcs">pcs (Pcs/Butir)</option>
                                                <option value="pack">pack (Pack)</option>
                                                <option value="box">box (Box)</option>
                                            </select>
                                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stok Saat Ini</label>
                                            {{-- PERUBAHAN DI SINI: step="1" untuk angka bulat --}}
                                            <input type="number" step="1" wire:model="stock"
                                                class="form-control @error('stock') is-invalid @enderror"
                                                placeholder="Contoh: 10000 (untuk 10.000 gram)">
                                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Satuan (Modal)</label>
                                            {{-- Harga modal tetap boleh desimal --}}
                                            <input type="number" step="0.01" wire:model="unit_price"
                                                class="form-control @error('unit_price') is-invalid @enderror"
                                                placeholder="Contoh: 15 (untuk 15/gram) atau 15000 (untuk 15000/kg)">
                                            @error('unit_price') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi (Opsional)</label>
                                    <textarea wire:model="description"
                                        class="form-control @error('description') is-invalid @enderror" rows="3"
                                        placeholder="Deskripsi singkat item..."></textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                                <button type="submit"
                                    class="btn btn-primary">{{ $inventoryId ? 'Perbarui' : 'Simpan' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Backdrop -->
            <div class="modal-backdrop fade show"></div>
        </div>
    @endif

    @push('js')
        <script>
            document.addEventListener('livewire:init', () => {

                // 1. Listener untuk Notifikasi (Sukses/Gagal)
                Livewire.on('notify', (data) => {
                    Swal.fire({
                        icon: data.icon ?? 'error',
                        title: data.icon === 'error' ? 'Gagal!' : 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });


                // 2. Listener untuk Konfirmasi Hapus
                Livewire.on('confirmDelete', (data) => {
                    // Ambil ID dengan aman (bisa berupa object atau value langsung)
                    const id = data.id || data;

                    Swal.fire({
                        title: 'Yakin hapus bahan ini?',
                        text: "Data stok akan hilang permanen. Pastikan bahan ini tidak dipakai di resep manapun.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim sinyal balik ke PHP: function delete($id)
                            Livewire.dispatch('deleteConfirmed', { id: id });
                        }
                    });
                });

                // 3. Helpers untuk Modal (Opsional, jika pakai Bootstrap Modal biasa)
                // Menutup modal saat event 'close-modal' didispatch dari PHP
                Livewire.on('close-modal', () => {
                    $('#inventoryModal').modal('hide');
                });
            });
        </script>
    @endpush
</div>

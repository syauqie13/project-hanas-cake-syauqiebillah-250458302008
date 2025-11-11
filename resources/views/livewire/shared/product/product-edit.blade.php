<div>
    <!-- resources/views/livewire/shared/product/product-edit.blade.php -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="border-0 shadow-lg modal-content rounded-3">
                <form wire:submit.prevent="update">
                    <!-- Header -->
                    <div class="pb-0 border-0 modal-header">
                        <h5 class="modal-title fw-bold text-dark" id="editModalLabel">
                            <i class="mr-2 fas fa-edit text-warning"></i> Edit Produk
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="$dispatch('hideEditModal')">
                            <span aria-hidden="true" class="fs-4">&times;</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <!-- Nama Produk -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Nama Produk</label>
                            <input type="text" class="rounded-lg shadow-sm form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama produk" wire:model.live.debounce.300ms="name">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Slug</label>
                            <input type="text" class="rounded-lg shadow-sm form-control @error('slug') is-invalid @enderror"
                                placeholder="Slug otomatis dari nama (bisa diubah)" wire:model.defer="slug" readonly>
                            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Kategori</label>
                            <select class="rounded-lg shadow-sm form-control @error('categoryId') is-invalid @enderror" wire:model.defer="categoryId">
                                <option value="">-- Pilih Kategori --</option>
                                @if(!empty($categories))
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('categoryId') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Harga -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Harga</label>
                            <input type="number" class="rounded-lg shadow-sm form-control @error('price') is-invalid @enderror"
                                placeholder="Masukkan harga produk" wire:model.defer="price" min="0" step="100">
                            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Stok -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Stok</label>
                            <input type="number" class="rounded-lg shadow-sm form-control @error('stock') is-invalid @enderror"
                                placeholder="Masukkan stok produk" wire:model.defer="stock" min="0" step="1">
                            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Diskon -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Diskon (%)</label>
                            <input type="number" class="rounded-lg shadow-sm form-control @error('discount') is-invalid @enderror"
                                placeholder="Masukkan diskon (0-100)" wire:model.defer="discount" min="0" max="100"
                                step="1">
                            @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- ============================================= --}}
                        {{-- === BAGIAN BARU UNTUK PRE-ORDER (PO) === --}}
                        {{-- ============================================= --}}

                        <!-- Checkbox Open PO -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_po_edit" wire:model.live="is_po">
                            <label class="form-check-label fw-semibold" for="is_po_edit">Produk Pre-Order (PO)?</label>
                        </div>

                        <!-- Batas Waktu PO (Hanya muncul jika PO dicentang) -->
                        @if($is_po)
                        <div class="mb-3 form-group fade-in" wire:key="po-deadline-wrapper-edit">
                            <label class="fw-semibold">Batas Waktu PO (Deadline)</label>
                            <input type="date" class="rounded-lg shadow-sm form-control @error('po_deadline') is-invalid @enderror"
                                   wire:model.defer="po_deadline">
                            @error('po_deadline') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @endif
                        {{-- ============================================= --}}
                        {{-- === AKHIR BAGIAN BARU === --}}
                        {{-- ============================================= --}}

                        <!-- Gambar Produk -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Gambar Produk (Opsional)</label>
                            <input type="file" class="form-control" wire:model="image" accept="image/*">

                            <!-- Preview gambar baru -->
                            @if ($image)
                                <div class="mt-2">
                                    <label class="text-muted small">Gambar baru:</label><br>
                                    <img src="{{ $image->temporaryUrl() }}" class="mt-1 rounded shadow-sm"
                                        alt="Preview baru" style="width: 120px; height: auto;">
                                </div>

                            <!-- Preview gambar lama (HANYA jika tidak ada gambar baru) -->
                            @elseif ($old_image)
                                <div class="mt-2">
                                    <label class="text-muted small">Gambar saat ini:</label><br>
                                    <img src="{{ asset('storage/' . $old_image) }}" class="mt-1 rounded shadow-sm"
                                        alt="Gambar lama" style="width: 120px; height: auto;">
                                </div>
                            @endif

                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="pt-0 border-0 modal-footer">
                        <button type="button" class="border btn btn-light" data-dismiss="modal"
                            wire:click="$dispatch('hideEditModal')">
                            <i class="mr-1 fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="shadow-sm btn btn-primary">
                            <span wire:loading.remove wire:target="update"><i class="mr-1 fas fa-save"></i> Simpan Perubahan</span>
                            <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i> Memperbarui...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            window.addEventListener('showEditModal', () => {
                $('#editModal').modal('show');
            });

            window.addEventListener('hideEditModal', () => {
                $('#editModal').modal('hide');
            });

            // Tambahan: supaya backdrop hilang total setelah Livewire refresh
            document.addEventListener("livewire:navigated", () => {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            });
        </script>

        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail.icon || 'success', // Ambil icon dari event
                    title: event.detail.icon === 'error' ? 'Gagal!' : 'Berhasil!', // Judul dinamis
                    text: event.detail.message || 'Aksi berhasil dijalankan!',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        </script>
    @endpush


</div>

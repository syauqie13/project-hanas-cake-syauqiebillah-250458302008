<div>
    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="border-0 shadow-lg modal-content rounded-3">
                <form wire:submit.prevent="save">
                    <!-- Header -->
                    <div class="pb-0 border-0 modal-header">
                        <h5 class="modal-title fw-bold text-dark" id="createModalLabel">
                            <i class="mr-2 fas fa-box-open text-primary"></i> Tambah Produk
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="closeModal">
                            <span aria-hidden="true" class="fs-4">&times;</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Kategori -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Kategori</label>
                            <select class="rounded-lg shadow-sm form-control @error('category_id') is-invalid @enderror"
                                wire:model.defer="category_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Nama Produk</label>
                            <input type="text"
                                class="rounded-lg shadow-sm form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama produk" wire:model.live.debounce.300ms="name">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Slug</label>
                            <input type="text"
                                class="rounded-lg shadow-sm form-control @error('slug') is-invalid @enderror"
                                placeholder="Slug otomatis dari nama" wire:model.defer="slug" readonly>
                            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Harga -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Harga</label>
                            <input type="number"
                                class="rounded-lg shadow-sm form-control @error('price') is-invalid @enderror"
                                placeholder="Masukkan harga produk" wire:model.defer="price" min="0" step="100">
                            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Stok -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Stok</label>
                            <input type="number"
                                class="rounded-lg shadow-sm form-control @error('stock') is-invalid @enderror"
                                placeholder="Masukkan stok" wire:model.defer="stock" min="0">
                            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Diskon -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Diskon (%)</label>
                            <input type="number"
                                class="rounded-lg shadow-sm form-control @error('discount') is-invalid @enderror"
                                placeholder="Masukkan diskon (opsional)" wire:model.defer="discount" min="0" max="100"
                                step="1">
                            @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- ============================================= --}}
                        {{-- === BAGIAN BARU UNTUK PRE-ORDER (PO) === --}}
                        {{-- ============================================= --}}

                        <!-- Checkbox Open PO -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_po_create" wire:model.live="is_po">
                            <label class="form-check-label fw-semibold" for="is_po_create">Produk Pre-Order
                                (PO)?</label>
                        </div>

                        <!-- Batas Waktu PO (Hanya muncul jika PO dicentang) -->
                        @if($is_po)
                            <div class="mb-3 form-group fade-in" wire:key="po-deadline-wrapper">
                                <label class="fw-semibold">Batas Waktu PO (Deadline)</label>
                                <input type="date"
                                    class="rounded-lg shadow-sm form-control @error('po_deadline') is-invalid @enderror"
                                    wire:model.defer="po_deadline">
                                @error('po_deadline') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @endif
                        {{-- ============================================= --}}
                        {{-- === AKHIR BAGIAN BARU === --}}
                        {{-- ============================================= --}}


                        <!-- Gambar Produk -->
                        <div class="mb-3 form-group">
                            <label class="fw-semibold">Gambar Produk</label>
                            <input type="file" class="form-control" wire:model="image" accept="image/*">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 rounded shadow-sm" alt="Preview"
                                    style="width: 120px; height: auto;">
                            @endif
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>


                    <!-- Footer -->
                    <div class="pt-0 border-0 modal-footer">
                        <button type="button" class="border btn btn-light" data-dismiss="modal" wire:click="closeModal">
                            <i class="mr-1 fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="shadow-sm btn btn-primary">
                            <span wire:loading.remove wire:target="save"><i class="mr-1 fas fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i>
                                Menyimpan...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Tampilkan modal ketika event Livewire dipanggil
            // Tampilkan modal create
            window.addEventListener('show-create-modal', () => {
                $('#createModal').modal('show');
            });

            // Tutup modal create
            window.addEventListener('hide-create-modal', () => {
                $('#createModal').modal('hide');
            });

            // Ini berlaku untuk SEMUA modal
            document.addEventListener("livewire:navigated", () => {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            });
        </script>
    @endpush
</div>

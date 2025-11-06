<div>
    @if($isOpen)
        <div class="" style="display: block;">
            <div class="modal fade show" id="recipeModal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atur Resep untuk: <span
                                    class="font-weight-bold">{{ $product->name ?? '' }}</span></h5>
                            <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <!-- BAGIAN 1: FORM TAMBAH RESEP -->
                            <h6 class="mb-3">Tambah Bahan Baku</h6>
                            <form wire:submit.prevent="addRecipe">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Pilih Bahan Baku</label>
                                            <select wire:model="inventory_id"
                                                class="form-control @error('inventory_id') is-invalid @enderror">
                                                <option value="">-- Pilih Bahan Baku --</option>
                                                @foreach($allInventories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->stock }}
                                                        {{ $item->unit }})</option>
                                                @endforeach
                                            </select>
                                            @error('inventory_id') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input type="number" step="0.01" wire:model="quantity_used"
                                                class="form-control @error('quantity_used') is-invalid @enderror"
                                                placeholder="Cth: 150">
                                            @error('quantity_used') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class_="form-group w-100">
                                            <button type="submit" class="btn btn-primary w-100">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <hr>

                            <!-- BAGIAN 2: DAFTAR RESEP SAAT INI -->
                            <h6 class="mb-3">Resep Saat Ini ({{ $currentRecipes->count() }} item)</h6>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table mb-0 table-striped table-sm">
                                    <thead class="thead-light" style="position: sticky; top: 0;">
                                        <tr>
                                            <th>Bahan Baku</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-right" style="width: 50px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($currentRecipes as $recipe)
                                            <tr>
                                                <td class="align-middle font-weight-600">{{ $recipe->inventory->name }}</td>
                                                <td class="text-center align-middle">{{ $recipe->quantity_used }}</td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-light">{{ $recipe->inventory->unit }}</span>
                                                </td>
                                                <td class="text-right align-middle">
                                                    <button class="btn btn-sm btn-icon btn-danger"
                                                        wire:click="removeRecipe({{ $recipe->id }})"
                                                        wire:confirm="Yakin hapus bahan baku ini dari resep?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-4 text-center text-muted">
                                                    <i class="fas fa-book-open"></i> Resep masih kosong.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Selesai</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Backdrop -->
            <div class="modal-backdrop fade show"></div>
        </div>
    @endif
</div>

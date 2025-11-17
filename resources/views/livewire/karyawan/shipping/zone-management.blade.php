<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Manajemen Zona Pengiriman</h1>
                <button wire:click="create" class="shadow-sm btn btn-primary">
                    <i class="mr-2 fas fa-plus-circle"></i> Tambah Zona
                </button>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Daftar Zona & Ongkir</h4>
                                <div class="card-header-form">
                                    <div class="input-group">
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari zona...">

                                    </div>
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Zona / Area</th>
                                                <th>Ongkos Kirim</th>
                                                <th class="text-center">Wajib Konfirmasi WA?</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($zones as $index => $zone)
                                                <tr>
                                                    <td>{{ $zones->firstItem() + $index }}</td>
                                                    <td class="font-weight-bold text-dark">{{ $zone->name }}</td>
                                                    <td>Rp {{ number_format($zone->price, 0, ',', '.') }}</td>
                                                    <td class="text-center">
                                                        @if($zone->requires_confirmation)
                                                            <span class="badge badge-warning"><i class="mr-1 fas fa-check"></i>
                                                                Ya (Manual)</span>
                                                        @else
                                                            <span class="badge badge-success">Otomatis</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button wire:click="edit({{ $zone->id }})"
                                                            class="mr-1 btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click="deleteConfirm({{ $zone->id }})"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="py-4 text-center">Belum ada zona pengiriman.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right card-footer">
                                {{ $zones->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div wire:ignore.self class="modal fade" id="zoneModal" tabindex="-1" role="dialog" aria-labelledby="zoneModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoneModalLabel">{{ $zoneId ? 'Edit Zona' : 'Tambah Zona Baru' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Zona / Area</label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Tangerang Kota (Max 5km)">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Ongkos Kirim (Rp)</label>
                            <input type="number" wire:model="price"
                                class="form-control @error('price') is-invalid @enderror" placeholder="Contoh: 15000">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Isi 0 jika gratis ongkir atau jika harga ditentukan
                                nanti (konfirmasi WA).</small>
                        </div>

                        <div class="form-group">
                            <div class="control-label">Wajib Konfirmasi via WA?</div>
                            <label class="pl-0 mt-2 custom-switch">
                                <input type="checkbox" wire:model="requires_confirmation" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">
                                    Aktifkan ini untuk area jauh/tidak terjangkau. Pelanggan tidak bisa langsung bayar
                                    sebelum konfirmasi.
                                </span>
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Handle Modal
            window.addEventListener('show-zone-modal', () => { $('#zoneModal').modal('show'); });
            window.addEventListener('hide-zone-modal', () => { $('#zoneModal').modal('hide'); });

            // SweetAlert Listeners
            window.addEventListener('notify', event => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: event.detail.icon || 'success',
                        title: event.detail.icon === 'error' ? 'Gagal!' : 'Berhasil!',
                        text: event.detail.message,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            });

            // PERBAIKAN UTAMA DI SINI (Sesuai kode Anda)
            window.addEventListener('confirmDelete', function (event) {
                // Ambil ID dari parameter event
                // Jika event.detail adalah object, ambil .id
                // Jika langsung ID, ambil langsung.
                let id = event.detail.id || event.detail;

                Swal.fire({
                    title: "Yakin ingin menghapus zona ini?",
                    text: "Data zona ini akan dihapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, hapus",
                    cancelButtonText: "Batal",
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Dispatch dengan parameter 'id' yang jelas
                        Livewire.dispatch('deleteConfirmed', { id: id });
                    }
                });
            });
        </script>
    @endpush
</div>

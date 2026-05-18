@push('styles')
    <link rel="stylesheet" href="{{ asset('css-app.css') }}">
@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Manajemen Store</h1>
                </div>

                @auth
                    @if (Auth::user()->role === 'karyawan' || Auth::user()->role === 'admin')
                        <button wire:click="create"
                            class="shadow-sm btn btn-primary d-flex align-items-center">
                            <i class="mr-2 fas fa-store"></i> Tambah Store
                        </button>
                    @endif
                @endauth
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Store</h4>
                                <div class="card-header-form">
                                    <div class="input-group">
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari nama store...">
                                    </div>
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead class="text-white">
                                            <tr>
                                                <th scope="col" class="text-center align-middle" style="width: 60px;">No</th>
                                                <th scope="col" class="align-middle">Nama Store</th>
                                                <th scope="col" class="text-center align-middle">Alamat</th>
                                                <th scope="col" class="text-center align-middle">Latitude</th>
                                                <th scope="col" class="text-center align-middle">Longitude</th>
                                                <th scope="col" class="text-center align-middle">Jam Buka</th>
                                                <th scope="col" class="text-center align-middle">Jam Tutup</th>
                                                <th scope="col" class="text-center align-middle">Status</th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($stores as $index => $store)
                                                <tr class="hover-highlight">
                                                    <td class="text-center align-middle text-muted font-weight-bold">
                                                        {{ $stores->firstItem() + $index }}
                                                    </td>
                                                    <td class="align-middle font-weight-600 text-dark">
                                                        <span class="badge badge-primary shadow-sm">{{ $store->name }}</span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $store->address }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $store->latitude }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $store->longitude }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $store->open_time }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $store->close_time }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if($store->is_active)
                                                            <span class="px-3 py-2 shadow-sm badge badge-success">
                                                                <i class="mr-1 fas fa-check-circle"></i> Aktif
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-2 shadow-sm badge badge-danger">
                                                                <i class="mr-1 fas fa-times-circle"></i> Nonaktif
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <div class="dropdown">
                                                            <button class="border btn btn-sm btn-light dropdown-toggle"
                                                                type="button" id="dropdownMenuButton{{ $store->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border-radius: 10px;">
                                                                <i class="fas fa-ellipsis-v text-secondary"></i>
                                                            </button>
                                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                                aria-labelledby="dropdownMenuButton{{ $store->id }}">
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="edit({{ $store->id }})">
                                                                    <i class="mr-2 fas fa-edit text-warning"></i> Edit
                                                                </button>
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center text-danger"
                                                                    wire:click="deleteConfirm({{ $store->id }})">
                                                                    <i class="mr-2 fas fa-trash-alt"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="py-5 text-center text-muted">
                                                        <i class="mb-3 fas fa-ticket-alt fa-2x"></i>
                                                        <div class="mb-0 h6">Belum ada data voucher</div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right card-footer">
                                <nav class="d-inline-block">
                                    {{ $stores->links('livewire::bootstrap') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Create & Edit Store -->
        @if($isOpen)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5); z-index: 1050;"
                aria-modal="true">

                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="rounded-lg shadow-lg modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark">
                                <i class="mr-2 fas fa-ticket-alt text-primary"></i> {{ $storeId ? 'Edit Store' : 'Tambah Store Baru' }}
                            </h5>
                            <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit.prevent="store">
                            <div class="p-4 modal-body">
                                <div class="form-group">
                                    <label>Nama Store <span class="text-danger">*</span></label>
                                    <input wire:model="name" type="text" class="form-control text-uppercase" placeholder="Contoh: Hana's Cake Mauk" required>
                                    @error('name') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Alamat <span class="text-danger">*</span></label>
                                    <input wire:model="address" type="text" class="form-control text-uppercase" placeholder="Contoh: Jl. Raya Mauk No. 123" required>
                                    @error('address') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Latitude <span class="text-danger">*</span></label>
                                        <input wire:model="latitude" type="text" class="form-control" placeholder="Contoh: -6.123456" required>
                                        @error('latitude') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Longitude <span class="text-danger">*</span></label>
                                        <input wire:model="longitude" type="text" class="form-control" placeholder="Contoh: 106.123456" required>
                                        @error('longitude') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Jam Buka <span class="text-danger">*</span></label>
                                        <input wire:model="open_time" type="time" class="form-control" required>
                                        @error('open_time') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>                                    
                                    <div class="form-group col-md-6">
                                        <label>Jam Tutup <span class="text-danger">*</span></label>
                                        <input wire:model="close_time" type="time" class="form-control" required>
                                        @error('close_time') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                                <button type="submit" class="shadow-sm btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('js')
        <!-- Notifikasi sukses -->
        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail[0].icon || 'success',
                    title: event.detail[0].icon === 'error' ? 'Gagal!' : 'Berhasil!',
                    text: event.detail[0].message || 'Aksi berhasil dijalankan!',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        </script>

        <!-- Konfirmasi hapus -->
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirmDelete', (data) => {
                    const id = data.id || (data[0] ? data[0].id : null);
                    if (!id) return;

                    Swal.fire({
                        title: 'Yakin hapus store ini?',
                        text: "Data store akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('deleteConfirmed', { id: id });
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('livewire:navigated', () => {
                if ($('[data-toggle="dropdown"]').length) {
                    $('[data-toggle="dropdown"]').dropdown('dispose');
                    $('[data-toggle="dropdown"]').dropdown();
                }
                if ($('[data-toggle="tooltip"]').length) {
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    $('[data-toggle="tooltip"]').tooltip();
                }
                if (jQuery().nicescroll) {
                    $(".main-sidebar").getNiceScroll().resize();
                }
            });
        </script>
    @endpush
</div>

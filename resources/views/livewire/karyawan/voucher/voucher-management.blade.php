@push('styles')
    <link rel="stylesheet" href="{{ asset('css-app.css') }}">
@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Manajemen Voucher</h1>
                </div>

                @auth
                    @if (Auth::user()->role === 'karyawan' || Auth::user()->role === 'admin')
                        <button wire:click="create"
                            class="shadow-sm btn btn-primary d-flex align-items-center">
                            <i class="mr-2 fas fa-ticket-alt"></i> Tambah Voucher
                        </button>
                    @endif
                @endauth
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Voucher</h4>
                                <div class="card-header-form">
                                    <div class="input-group">
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari kode voucher...">
                                    </div>
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead class="text-white">
                                            <tr>
                                                <th scope="col" class="text-center align-middle" style="width: 60px;">No</th>
                                                <th scope="col" class="align-middle">Kode Voucher</th>
                                                <th scope="col" class="text-center align-middle">Diskon</th>
                                                <th scope="col" class="text-center align-middle">Min. Belanja</th>
                                                <th scope="col" class="text-center align-middle">Berlaku Sampai</th>
                                                <th scope="col" class="text-center align-middle">Status</th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vouchers as $index => $voucher)
                                                <tr class="hover-highlight">
                                                    <td class="text-center align-middle text-muted font-weight-bold">
                                                        {{ $vouchers->firstItem() + $index }}
                                                    </td>
                                                    <td class="align-middle font-weight-600 text-dark">
                                                        <span class="badge badge-primary shadow-sm">{{ $voucher->code }}</span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if($voucher->type == 'nominal')
                                                            Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                                        @else
                                                            {{ $voucher->value }}% 
                                                            @if($voucher->max_discount)
                                                                <br><small class="text-muted">(Maks: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }})</small>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $voucher->min_purchase ? 'Rp ' . number_format($voucher->min_purchase, 0, ',', '.') : 'Tanpa Minimal' }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('d M Y H:i') : 'Selamanya' }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if($voucher->is_active)
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
                                                                type="button" id="dropdownMenuButton{{ $voucher->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border-radius: 10px;">
                                                                <i class="fas fa-ellipsis-v text-secondary"></i>
                                                            </button>
                                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                                aria-labelledby="dropdownMenuButton{{ $voucher->id }}">
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="edit({{ $voucher->id }})">
                                                                    <i class="mr-2 fas fa-edit text-warning"></i> Edit
                                                                </button>
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center text-danger"
                                                                    wire:click="deleteConfirm({{ $voucher->id }})">
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
                                    {{ $vouchers->links('livewire::bootstrap') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Create & Edit Voucher -->
        @if($isOpen)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; padding-right: 17px; background-color: rgba(0,0,0,0.5); z-index: 1050;"
                aria-modal="true">

                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="rounded-lg shadow-lg modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark">
                                <i class="mr-2 fas fa-ticket-alt text-primary"></i> {{ $voucherId ? 'Edit Voucher' : 'Tambah Voucher Baru' }}
                            </h5>
                            <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form wire:submit.prevent="store">
                            <div class="p-4 modal-body">
                                <div class="form-group">
                                    <label>Kode Voucher <span class="text-danger">*</span></label>
                                    <input wire:model="code" type="text" class="form-control text-uppercase" placeholder="Contoh: MERDEKA20" required>
                                    @error('code') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Tipe Diskon <span class="text-danger">*</span></label>
                                        <select wire:model.live="type" class="form-control" required>
                                            <option value="nominal">Nominal (Rp)</option>
                                            <option value="percentage">Persentase (%)</option>
                                        </select>
                                        @error('type') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nilai Diskon <span class="text-danger">*</span></label>
                                        <input wire:model="value" type="number" class="form-control" required>
                                        @error('value') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                </div>
                                @if($type == 'percentage')
                                    <div class="form-group">
                                        <label>Maksimal Diskon (Rp) <small class="text-muted">(Opsional)</small></label>
                                        <input wire:model="max_discount" type="number" class="form-control">
                                        @error('max_discount') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Minimal Belanja (Rp) <small class="text-muted">(Opsional)</small></label>
                                    <input wire:model="min_purchase" type="number" class="form-control">
                                    @error('min_purchase') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Berlaku Sampai <small class="text-muted">(Opsional)</small></label>
                                    <input wire:model="valid_until" type="datetime-local" class="form-control">
                                    @error('valid_until') <div class="mt-1 text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input wire:model="is_active" type="checkbox" class="custom-control-input" id="isActiveCheck">
                                        <label class="custom-control-label" for="isActiveCheck">Voucher Aktif</label>
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
                        title: 'Yakin hapus voucher ini?',
                        text: "Data voucher akan dihapus permanen.",
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

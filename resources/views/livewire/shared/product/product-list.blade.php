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
                    <h1>Data Produk</h1>
                </div>

                @auth
                    @if (Auth::user()->role === 'karyawan')
                        <!-- Tombol Tambah Produk (Sekarang memanggil event) -->
                        <button wire:click="$dispatch('openCreateModal')" class="shadow-sm btn btn-primary d-flex align-items-center">
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
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari produk...">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped table-md">
                                        <thead class="text-white">
                                            <tr>
                                                <th scope="col" class="text-center align-middle" style="width: 60px;">No</th>
                                                <th scope="col" class="align-middle">Nama Produk</th>
                                                <th scope="col" class="text-center align-middle">Kategori</th>
                                                <th scope="col" class="text-center align-middle">Harga</th>
                                                <th scope="col" class="text-center align-middle">Stok</th>
                                                <th scope="col" class="text-center align-middle">Diskon</th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">Aksi</th>
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

                                                                <div class="dropdown-divider"></div>

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
                                            @endforelse {{-- <-- INI ADALAH PERBAIKANNYA (sebelumnya @empty) --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right card-footer">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: "Data produk dan resep terkait akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('deleteConfirmed', { id: data.id });
                        }
                    });
                });
            });
        </script>
    @endpush
</div>

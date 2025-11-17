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

        /* Style untuk pagination agar rapi */
        .card-footer .pagination {
            margin-bottom: 0;
        }
    </style>
@endpush

<div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Data Kategori</h1>
                </div>

                <!-- Tombol Tambah Kategori -->
                @auth
                    @if (Auth::user()->role === 'karyawan')
                        <button wire:click="create" class="shadow-sm btn btn-primary d-flex align-items-center">
                            <i class="mr-2 fas fa-tag"></i> Tambah Kategori
                        </button>
                    @endif
                @endauth
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <!-- ====================================== -->
                            <!-- === 1. TAMBAHKAN SEARCH BAR DI SINI === -->
                            <!-- ====================================== -->
                            <div class="card-header">
                                <h4 class="card-title">Daftar Kategori</h4>
                                <div class="card-header-form">
                                    <div class="input-group">
                                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                            placeholder="Cari nama kategori...">
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
                                                <th scope="col" class="align-middle">Nama Kategori</th>
                                                <th scope="col" class="text-center align-middle">Jumlah Produk</th>
                                                <th scope="col" class="text-center align-middle" style="width: 100px;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($categories as $index => $category) {{-- Tambahkan $index --}}
                                                <tr class="hover-highlight">
                                                    <td class="text-center align-middle text-muted font-weight-bold">
                                                        <!-- ====================================== -->
                                                        <!-- === 2. PERBAIKI PENOMORAN UNTUK PAGINASI === -->
                                                        <!-- ====================================== -->
                                                        {{ $categories->firstItem() + $index }}
                                                    </td>

                                                    <td class="align-middle font-weight-600 text-dark">
                                                        {{ $category->name }}
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <span class="px-3 py-2 shadow-sm badge badge-info">
                                                            <!-- ====================================== -->
                                                            <!-- === 3. PERBAIKI JUMLAH PRODUK (EFISIEN) === -->
                                                            <!-- ====================================== -->
                                                            {{ $category->products_count }}
                                                        </span>
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <div class="dropdown">
                                                            <button class="border btn btn-sm btn-light dropdown-toggle"
                                                                type="button" id="dropdownMenuButton{{ $category->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border-radius: 10px;">
                                                                <i class="fas fa-ellipsis-v text-secondary"></i>
                                                            </button>
                                                            <div class="shadow-sm dropdown-menu dropdown-menu-right animated--fade-in"
                                                                aria-labelledby="dropdownMenuButton{{ $category->id }}">
                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center"
                                                                    wire:click="edit({{ $category->id }})">
                                                                    <i class="mr-2 fas fa-edit text-warning"></i> Edit
                                                                </button>

                                                                <button type="button"
                                                                    class="cursor-pointer dropdown-item d-flex align-items-center text-danger"
                                                                    wire:click="deleteConfirm({{ $category->id }})">
                                                                    <i class="mr-2 fas fa-trash-alt"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-5 text-center text-muted">
                                                        <i class="mb-3 fas fa-tags fa-2x"></i>
                                                        <div class="mb-0 h6">Belum ada data kategori</div>
                                                        <small>Tambahkan kategori baru untuk mulai mengelola produk.</small>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="text-right card-footer">
                                <nav class="d-inline-block">
                                    {{ $categories->links('livewire::bootstrap') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- Modal Create & Edit Produk -->
    <livewire:shared.category.category-create />
    <livewire:shared.category.category-edit />

    @push('js')
        <!-- JS Libraries -->
        <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>

        <!-- Page Specific JS File -->
        <script src="{{ asset('assets/js/page/components-table.js') }}"></script>

        <!-- Notifikasi sukses -->
        <script>
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
        </script>

        <!-- Konfirmasi hapus -->
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirmDelete', (data) => {
                    // Ambil ID (sudah aman)
                    const id = data.id || (data[0] ? data[0].id : null);

                    Swal.fire({
                        title: 'Yakin hapus?',
                        // Pesan khusus Kategori
                        text: "Data kategori akan dihapus. Ini mungkin mempengaruhi produk terkait.",
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
    @endpush
</div>

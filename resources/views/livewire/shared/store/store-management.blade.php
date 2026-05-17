<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manajemen Store / Cabang</h3>
                    <!-- <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Store</button> -->
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Cabang</th>
                                <th>Alamat</th>
                                <th>Jam Operasional</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stores as $index => $store)
                                <tr>
                                    <td>{{ $stores->firstItem() + $index }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->address }}</td>
                                    <td>{{ \Carbon\Carbon::parse($store->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($store->close_time)->format('H:i') }}</td>
                                    <td>
                                        @if($store->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button wire:click="delete({{ $store->id }})" wire:confirm="Yakin ingin menghapus store ini?" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data store.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $stores->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

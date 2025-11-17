<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Hasil Pencarian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Menampilkan hasil untuk: <strong>"{{ $query }}"</strong></div>
                </div>
            </div>

            <div class="section-body">

                {{-- Jika tidak ada hasil --}}
                @if(strlen($query) <= 2)
                    <div class="alert alert-info">
                        Masukkan minimal 3 huruf untuk memulai pencarian.
                    </div>
                @elseif($products->isEmpty() && $orders->isEmpty() && $customers->isEmpty() && $inventories->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada hasil yang ditemukan untuk "{{ $query }}".
                    </div>
                @endif

                <div class="row">
                    {{-- Hasil Produk & Inventaris --}}
                    <div class="col-lg-6">
                        @if($products->count() > 0)
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Produk Ditemukan ({{ $products->count() }})</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($products as $product)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#">{{ $product->name }}</a>
                                                <span class="badge badge-primary badge-pill">Stok: {{ $product->stock }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if($inventories->count() > 0)
                            <div class="card card-info">
                                <div class="card-header">
                                    <h4>Bahan Baku Ditemukan ({{ $inventories->count() }})</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($inventories as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#">{{ $item->name }}</a>
                                                <span class="badge badge-info badge-pill">Stok: {{ $item->stock }}
                                                    {{ $item->unit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Hasil Order & Pelanggan --}}
                    <div class="col-lg-6">
                        @if($orders->count() > 0)
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h4>Pesanan Ditemukan ({{ $orders->count() }})</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($orders as $order)
                                            <li class="list-group-item">
                                                <a href="#">Order ID: {{ $order->id }}</a> ({{ $order->shipping_name }})
                                                <div class="float-right badge badge-warning">{{ $order->status }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if($customers->count() > 0)
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h4>Pelanggan Ditemukan ({{ $customers->count() }})</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($customers as $customer)
                                            <li class="list-group-item">
                                                <a href="#">{{ $customer->name }}</a>
                                                <div class="float-right text-muted small">{{ $customer->phone }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </section>
    </div>


</div>

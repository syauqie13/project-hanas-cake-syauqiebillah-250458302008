<x-layouts.struk>
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="col-12 col-md-10">
                    <div class="shadow-sm card card-primary">
                        <div class="card-header">
                            <h4>Order ID: {{ $order->merchant_order_id }}</h4>
                        </div>
                        <div class="card-body">

                            {{-- KOTAK STATUS --}}
                            @if($order->payment_status == 'paid' || $order->status == 'completed')
                                <div class="alert alert-success">
                                    <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Pembayaran Lunas!</h5>
                                    <p>Pembayaran telah dikonfirmasi oleh Midtrans (Settlement).</p>
                                </div>
                            @elseif($status_query == 'pending' || $order->payment_status == 'pending')
                                <div class="alert alert-warning">
                                    <h5 class="alert-heading"><i class="fas fa-hourglass-half"></i> Menunggu Pembayaran
                                    </h5>
                                    <p>Order telah dibuat. Menunggu pelanggan menyelesaikan pembayaran.</p>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <h5 class="alert-heading"><i class="fas fa-times-circle"></i> Pembayaran
                                        Gagal/Kedaluwarsa</h5>
                                    <p>Status pembayaran saat ini: {{ $order->payment_status }}</p>
                                </div>
                            @endif

                            {{-- DETAIL ORDER (Ringkasan) --}}
                            <ul class="mb-4 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Pelanggan:</span>
                                    <strong>{{ $order->customer->name ?? 'Guest' }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total:</span>
                                    <strong class="h5">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Metode Bayar:</span>
                                    <strong>{{ $order->payment_method }}</strong>
                                </li>
                            </ul>

                            {{-- TOMBOL AKSI --}}
                            <div class="text-center">
                                <p class="text-muted">Klik tombol di bawah untuk mencetak struk (wajib jika Lunas).
                                </p>

                                <!-- Tombol Cetak Struk Manual -->
                                <a href="{{ route('karyawan.struk.print', $order->id) }}" target="_blank"
                                    class="btn btn-primary btn-lg shadow-sm {{ ($order->payment_status != 'paid' && $order->status != 'completed') ? 'disabled' : '' }}">
                                    <i class="mr-2 fas fa-print"></i>
                                    Cetak Struk Manual
                                </a>

                                <a href="{{ route('karyawan.pos') }}" class="ml-3 shadow-sm btn btn-secondary btn-lg">
                                    <i class="mr-2 fas fa-cash-register"></i>
                                    Transaksi Baru (Kembali ke POS)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</x-layouts.struk>

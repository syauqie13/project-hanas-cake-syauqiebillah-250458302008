<div wire:poll.60s>

    @push('js')
        <script>
            var revenueChart; // Chart 1: Pendapatan (Line)
            var categoryChart; // Chart 2: Kategori (Donut)

            // --- FUNGSI UNTUK MEMBUAT CHART ---
            function createAdminCharts(labels30Hari, data30Hari, labelsKategori, dataKategori) {
                var ctxRevenue = document.getElementById("revenueChart");
                var ctxCategory = document.getElementById("categoryChart");

                // 1. Buat Chart Pendapatan (Line)
                if (ctxRevenue) {
                    if (typeof revenueChart !== 'undefined') revenueChart.destroy();
                    revenueChart = new Chart(ctxRevenue.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: labels30Hari,
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: data30Hari,
                                borderWidth: 2,
                                backgroundColor: 'rgba(63, 82, 227, .2)',
                                borderColor: 'rgba(63, 82, 227, 1)',
                                pointRadius: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: { display: false },
                            scales: {
                                yAxes: [{ ticks: { beginAtZero: true, callback: function (v) { return 'Rp ' + v / 1000 + 'k'; } } }],
                                xAxes: [{ gridLines: { display: false } }]
                            },
                        }
                    });
                }

                // 2. Buat Chart Kategori (Donut)
                if (ctxCategory) {
                    if (typeof categoryChart !== 'undefined') categoryChart.destroy();
                    categoryChart = new Chart(ctxCategory.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: labelsKategori,
                            datasets: [{
                                data: dataKategori,
                                // Sediakan warna (bisa tambahkan lagi)
                                backgroundColor: ['#6777ef', '#ffa426', '#fc544b', '#3abaf4', '#34395e'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: { position: 'bottom' },
                        }
                    });
                }
            }

            // --- FUNGSI UNTUK UPDATE CHART (VIA POLLING) ---
            function updateAdminCharts(labels30Hari, data30Hari, labelsKategori, dataKategori) {
                if (typeof revenueChart !== 'undefined' && revenueChart.data) {
                    revenueChart.data.labels = labels30Hari;
                    revenueChart.data.datasets[0].data = data30Hari;
                    revenueChart.update();
                }
                if (typeof categoryChart !== 'undefined' && categoryChart.data) {
                    categoryChart.data.labels = labelsKategori;
                    categoryChart.data.datasets[0].data = dataKategori;
                    categoryChart.update();
                }
            }

            // --- EVENT LISTENERS ---
            document.addEventListener('livewire:navigated', () => {
                // Buat chart saat halaman di-load (via navigasi Livewire)
                createAdminCharts(
                    @json($chartLabels30Hari),
                    @json($chartData30Hari),
                    @json($chartLabelsKategori),
                    @json($chartDataKategori)
                );

                // Dengar event 'updateAdminCharts' dari PHP (saat polling)
                Livewire.on('updateAdminCharts', ({ labels30Hari, data30Hari, labelsKategori, dataKategori }) => {
                    updateAdminCharts(labels30Hari, data30Hari, labelsKategori, dataKategori);
                });
            });

        </script>
    @endpush


    {{-- Konten Halaman (Main Content) --}}
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard Admin</h1>
            </div>

            {{-- KARTU RINGKASAN (BULAN INI) --}}
            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pendapatan (Bulan Ini)</h4>
                            </div>
                            <div class="card-body">
                                Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Profit (Bulan Ini)</h4>
                            </div>
                            <div class="card-body">
                                Rp {{ number_format($totalProfitBulanIni, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Order (Bulan Ini)</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrderBulanIni }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pelanggan Baru (Bulan Ini)</h4>
                            </div>
                            <div class="card-body">
                                {{ $pelangganBaruBulanIni }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- GRAFIK & LIST --}}
            <div class="row">

                {{-- Kolom Kiri: Grafik Pendapatan 30 Hari --}}
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card" wire:ignore>
                        <div class="card-header">
                            <h4>Pendapatan 30 Hari Terakhir</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="182"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Grafik Kategori --}}
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card" wire:ignore>
                        <div class="card-header">
                            <h4>Penjualan per Kategori</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" height="182"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST STOK & PRODUK --}}
            <div class="row">
                {{-- Kolom Kiri: Stok Bahan Baku Menipis --}}
                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-danger">⚠️ Stok Bahan Baku Menipis</h4>
                        </div>
                        <div class="card-body">
                            @if($lowStockInventories->count() > 0)
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach($lowStockInventories as $item)
                                        <li class="media">
                                            <div class="media-body">
                                                <div class="float-right p-2 badge badge-danger">
                                                    Sisa {{ $item->stock }} {{ $item->unit }}
                                                </div>
                                                <div class="media-title">{{ $item->name }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-success">Stok bahan baku aman 👍</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Top 5 Produk Terlaris --}}
                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>🔥 Top 5 Produk Terlaris (Bulan Ini)</h4>
                        </div>
                        <div class="card-body">
                            @if($topProductsBulanIni->count() > 0)
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach($topProductsBulanIni as $item)
                                        <li class="media">
                                            @php
                                                $imageUrl = $item->product && $item->product->image
                                                    ? asset('storage/' . $item->product->image)
                                                    : asset('assets/img/news/img08.jpg');
                                            @endphp
                                            <img class="mr-3 rounded" width="50" height="50" src="{{ $imageUrl }}" alt="product"
                                                style="object-fit: cover;">
                                            <div class="media-body">
                                                <div class="float-right text-primary font-weight-bold">{{ $item->total_qty }}
                                                    pcs</div>
                                                <div class="media-title">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                                <span class="text-small text-muted">ID: {{ $item->product_id }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-muted">Belum ada penjualan bulan ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>


</div>

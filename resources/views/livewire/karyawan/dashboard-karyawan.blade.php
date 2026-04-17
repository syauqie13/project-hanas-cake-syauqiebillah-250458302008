<div wire:poll.5s>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css-app.css') }}">
    @endpush

    @push('js')

        <script>
            var myChart; // Deklarasikan di scope global agar bisa di-update

            // Fungsi untuk MEMBUAT chart (menggantikan index-0.js)
            function createStislaChart(hours, counts) {
                // Cek jika canvas ada
                var ctx = document.getElementById("myChart");
                if (!ctx) return;

                myChart = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: hours,
                        datasets: [{
                            label: 'Jumlah Order',
                            data: counts,
                            borderWidth: 2,
                            backgroundColor: 'rgba(63, 82, 227, .2)', // Warna Stisla
                            borderColor: 'rgba(63, 82, 227, 1)',     // Warna Stisla
                            pointBorderWidth: 0,
                            pointRadius: 3,
                            pointBackgroundColor: 'rgba(63, 82, 227, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 5 // Grafik akan melompat per 5 order
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                    }
                });
            }

            // Fungsi untuk UPDATE chart (dipanggil oleh polling)
            function updateStislaChart(hours, counts) {
                if (typeof myChart !== 'undefined' && myChart.data) {
                    myChart.data.labels = hours;
                    if (myChart.data.datasets.length > 0) {
                        myChart.data.datasets[0].data = counts;
                    }
                    myChart.update();
                }
            }

            document.addEventListener('livewire:navigated', () => {
                // 1. Buat chart baru setiap kali halaman dashboard di-load
                createStislaChart(@json($chartHours), @json($chartCounts));

                // 2. Dengar event 'updateDashboardCharts' dari PHP (saat polling)
                // Kita pakai 'Livewire.on' di sini
                Livewire.on('updateDashboardCharts', ({ hours, counts }) => {
                    updateStislaChart(hours, counts);
                });
            });
        </script>
    @endpush


    {{-- Konten Halaman (Main Content) --}}
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard Karyawan</h1>
            </div>

            {{-- KARTU RINGKASAN --}}
            <div class="row">

                {{-- Kartu 1: Pendapatan Hari Ini --}}
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-offline">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pendapatan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 2: Total Order Hari Ini --}}
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Order Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrder }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 3: Order Pending --}}
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon {{ $orderPending > 0 ? 'bg-danger' : 'bg-secondary' }}">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Order Pending</h4>
                            </div>
                            <div class="card-body {{ $orderPending > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ $orderPending }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- GRAFIK & LIST --}}
            <div class="row">

                {{-- Kolom Kiri: Grafik --}}
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card" wire:ignore>
                        <div class="card-header">
                            <h4>Statistik Penjualan (Per Jam Hari Ini)</h4>
                        </div>
                        <div class="card-body">
                            {{-- Chart.js Canvas --}}
                            <canvas id="myChart" height="182"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: List --}}
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">

                    {{-- Widget Stok Menipis --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-danger">⚠️ Stok Kue Menipis (< 10)</h4>
                        </div>
                        <div class="card-body">
                            @if($lowStockProducts->count() > 0)
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach($lowStockProducts as $product)
                                        <li class="media">
                                            <div class="media-body">
                                                <div class="float-right p-2 badge badge-danger">Sisa {{ $product->stock }}</div>
                                                <div class="media-title">{{ $product->name }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-success">Stok aman terkendali 👍</p>
                            @endif
                        </div>
                    </div>

                    {{-- Widget Top Produk --}}
                    <div class="card">
                        <div class="card-header">
                            <h4>🔥 Top 5 Produk Terlaris</h4>
                        </div>
                        <div class="card-body">
                            @if($topProducts->count() > 0)
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach($topProducts as $index => $item)
                                        <li class="media">
                                            {{-- Fallback image jika 'image' null --}}
                                            @php
                                                $imageUrl = $item->product && $item->product->image
                                                    ? asset('storage/' . $item->product->image)
                                                    : asset('assets/img/news/img08.jpg'); // Default image Stisla
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
                                <p class="text-center text-muted">Belum ada penjualan hari ini.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


</div>

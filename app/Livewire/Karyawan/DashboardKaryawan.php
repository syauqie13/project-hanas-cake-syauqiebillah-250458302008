<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')] // Pastikan ini sesuai dengan path layout Anda
class DashboardKaryawan extends Component
{

    public function render()
    {
        // 1. Ringkasan Angka (Hanya Hari Ini)
        $today = Carbon::today();

        // Menggunakan kolom 'total' dan 'lunas' sesuai DB Anda
        $totalPendapatan = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total');

        $totalOrder = Order::whereDate('created_at', $today)->count();

        // Menggunakan 'status' 'pending' sesuai DB Anda
        $orderPending = Order::where('status', 'pending')
            ->whereDate('created_at', $today) // Ditambahkan filter tanggal
            ->count();

        // 2. Grafik Jam Sibuk (Line Chart)
        $salesDataRaw = Order::selectRaw('HOUR(created_at) as hour, count(*) as count')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Normalisasi data (isi jam kosong dengan 0) agar grafik mulus
        $chartHours = [];
        $chartCounts = [];
        for ($i = 8; $i <= 21; $i++) { // Asumsi toko buka jam 8 pagi - 9 malam
            $chartHours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $chartCounts[] = $salesDataRaw[$i] ?? 0;
        }

        // 3. Produk Terlaris Hari Ini (Top 5)
        // Menggunakan 'product_id' dan 'jumlah' sesuai DB Anda
        $topProducts = OrderItem::select('product_id', DB::raw('sum(jumlah) as total_qty'))
            ->whereDate('created_at', $today)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->with('product') // Load relasi product untuk mengambil nama
            ->get();

        // 4. Stok Produk Menipis (Alert)
        // Menggunakan 'stock' dari tabel products
        $lowStockProducts = Product::where('stock', '<', 10) // Ambang batas 10
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Kirim data baru ke AlpineJS di frontend untuk update chart
        $this->dispatch('updateDashboardCharts', hours: $chartHours, counts: $chartCounts);

        // Kirim semua data ke view
        return view('livewire.karyawan.dashboard-karyawan', [
            'totalPendapatan' => $totalPendapatan,
            'totalOrder' => $totalOrder,
            'orderPending' => $orderPending,
            'chartHours' => $chartHours,
            'chartCounts' => $chartCounts,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}

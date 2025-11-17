<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class DashboardAdmin extends Component
{
    public function render()
    {
        // --- 1. PENGATURAN TANGGAL ---
        $bulanIniMulai = Carbon::now()->startOfMonth();
        $bulanIniSelesai = Carbon::now()->endOfMonth();
        $tgl30HariLalu = Carbon::now()->subDays(29)->startOfDay(); // 30 hari termasuk hari ini
        $today = Carbon::today();

        // --- 2. KARTU STATISTIK (BULAN INI) ---

        // KARTU 1: Total Pendapatan (Lunas)
        $totalPendapatanBulanIni = Order::whereBetween('created_at', [$bulanIniMulai, $bulanIniSelesai])
            ->where('payment_status', 'paid')
            ->sum('total');

        // KARTU 2: Total Order (Lunas)
        $totalOrderBulanIni = Order::whereBetween('created_at', [$bulanIniMulai, $bulanIniSelesai])
            ->where('payment_status', 'paid')
            ->count();

        // KARTU 3: Pelanggan Baru
        $pelangganBaruBulanIni = Customer::whereBetween('created_at', [$bulanIniMulai, $bulanIniSelesai])->count();

        // KARTU 4: Perkiraan PROFIT
        $totalCOGS = 0;
        $orderItems = OrderItem::whereHas('order', function ($q) use ($bulanIniMulai, $bulanIniSelesai) {
            $q->whereBetween('created_at', [$bulanIniMulai, $bulanIniSelesai])
                ->where('payment_status', 'paid');
        })
            ->with('product.recipes.inventory') // Eager load resep & bahan baku
            ->get();

        foreach ($orderItems as $item) {
            $cogsPerProduct = 0;
            if ($item->product && $item->product->recipes) {
                foreach ($item->product->recipes as $recipe) {
                    if ($recipe->inventory) {
                        // (Jumlah bahan dipakai) * (Harga bahan)
                        $cogsPerProduct += $recipe->quantity_used * $recipe->inventory->unit_price;
                    }
                }
            }
            $totalCOGS += $cogsPerProduct * $item->jumlah;
        }
        $totalProfitBulanIni = $totalPendapatanBulanIni - $totalCOGS;


        // --- 3. GRAFIK-GRAFIK ---

        // GRAFIK 1: Pendapatan Harian (30 Hari Terakhir) - LINE CHART
        $sales30Hari = Order::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('sum(total) as pendapatan')
        )
            ->whereBetween('created_at', [$tgl30HariLalu, $today])
            ->where('payment_status', 'paid')
            ->groupBy(DB::raw('DATE(created_at)')) // <-- PERBAIKAN: dari 'tanggal' menjadi DB::raw('DATE(created_at)')
            ->orderBy('tanggal', 'asc')
            ->pluck('pendapatan', 'tanggal');

        // Normalisasi data 30 hari (isi hari kosong dengan 0)
        $chartLabels30Hari = [];
        $chartData30Hari = [];
        $period = CarbonPeriod::create($tgl30HariLalu, $today);
        foreach ($period as $date) {
            $chartLabels30Hari[] = $date->format('d M');
            $chartData30Hari[] = $sales30Hari[$date->format('Y-m-d')] ?? 0;
        }

        // GRAFIK 2: Penjualan per Kategori (Bulan Ini) - DONUT CHART
        $salesPerKategori = OrderItem::select('categories.name as category_name', DB::raw('sum(order_items.subtotal) as total_sales'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$bulanIniMulai, $bulanIniSelesai])
            ->groupBy('categories.name')
            ->pluck('total_sales', 'category_name');

        $chartLabelsKategori = $salesPerKategori->keys();
        $chartDataKategori = $salesPerKategori->values();


        // --- 4. LIST (TABLET) ---

        // LIST 1: Top 5 Produk (Bulan Ini)
        $topProductsBulanIni = OrderItem::select('product_id', DB::raw('sum(jumlah) as total_qty'))
            ->whereBetween('created_at', [$bulanIniMulai, $bulanIniSelesai])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->with('product')
            ->get();

        // LIST 2: Stok Bahan Baku Menipis (Inventories)
        // Ambil 5 stok terendah dari 'bahan_baku'
        $lowStockInventories = Inventory::where('type', 'bahan_baku')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Dispatch data ke Chart.js di frontend
        $this->dispatch(
            'updateAdminCharts',
            labels30Hari: $chartLabels30Hari,
            data30Hari: $chartData30Hari,
            labelsKategori: $chartLabelsKategori,
            dataKategori: $chartDataKategori
        );

        return view('livewire.admin.dashboard-admin', [
            'totalPendapatanBulanIni' => $totalPendapatanBulanIni,
            'totalProfitBulanIni' => $totalProfitBulanIni,
            'totalOrderBulanIni' => $totalOrderBulanIni,
            'pelangganBaruBulanIni' => $pelangganBaruBulanIni,
            'topProductsBulanIni' => $topProductsBulanIni,
            'lowStockInventories' => $lowStockInventories,

            // Kirim data mentah ini untuk inisialisasi chart
            'chartLabels30Hari' => $chartLabels30Hari,
            'chartData30Hari' => $chartData30Hari,
            'chartLabelsKategori' => $chartLabelsKategori,
            'chartDataKategori' => $chartDataKategori,
        ]);
    }
}

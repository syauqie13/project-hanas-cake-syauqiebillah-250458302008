<?php

namespace App\Livewire\Karyawan\Pos;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use App\Exports\PosExport;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // <-- Tambahkan DB


#[Layout('components.layouts.app')]
class PosManagement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Properti Filter
    #[Url(keep: true)]
    public $search = '';

    #[Url(keep: true)]
    public $filterTanggal = 'today';

    #[Url(keep: true)]
    public $filterStatus = 'all';
    #[Url(keep: true)]
    public $filterPaymentMethod = 'all';

    // Properti Modal
    #[Locked]
    public $showDetailModal = false;
    public $selectedOrder;

    public function export()
    {
        $filename = 'laporan_pos_'
            . $this->filterTanggal . '_'
            . now()->format('Y-m-d_H-i-s')
            . '.xlsx';


        // Kirim semua filter saat ini ke Export Class
        return Excel::download(
            new PosExport($this->search, $this->filterStatus, $this->filterTanggal),
            $filename
        );
    }


    public function showDetail($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'customer', 'cashier'])
            ->where('order_type', 'pos')
            ->find($orderId);

        if ($this->selectedOrder) {
            $this->showDetailModal = true;
        }
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
    }

    public function resetTanggal()
    {
        $this->filterTanggal = 'today';
        $this->resetPage();
    }

    public function render()
    {
        // --- 1. BUAT KUERI DASAR (Base Query) ---
        $baseQuery = Order::where('order_type', 'pos');

        // --- 2. TERAPKAN FILTER GLOBAL (Tanggal & Search) ---
        // Terapkan Filter Tanggal
        switch ($this->filterTanggal) {
            case 'today':
                $baseQuery->whereDate('tanggal', Carbon::today());
                break;
            case 'week':
                $baseQuery->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $baseQuery->whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year);
                break;
        }

        // Terapkan Filter Pencarian
        if (strlen($this->search) > 2) {
            $baseQuery->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('merchant_order_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('cashier', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // --- 3. HITUNG STATISTIK KARTU (Dari Kueri yg sudah difilter) ---
        // Kloning kueri dasar SEBELUM filter status diterapkan
        $statsQuery = clone $baseQuery;

        $stats = $statsQuery->select(
            DB::raw('SUM(CASE WHEN payment_method = "tunai" THEN total ELSE 0 END) as total_pendapatan_offline'),
            DB::raw('SUM(CASE WHEN payment_method = "midtrans" THEN total ELSE 0 END) as total_pendapatan_online'),
            DB::raw('COUNT(*) as total_pesanan'),
            DB::raw('SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as total_diproses'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as total_selesai')
        )->first();


        // --- 4. TERAPKAN FILTER STATUS (Hanya untuk Tabel) ---
        if ($this->filterStatus !== 'all') {
            $baseQuery->where('status', $this->filterStatus);
        }

        if ($this->filterPaymentMethod !== 'all') {
            $baseQuery->where('payment_method', $this->filterPaymentMethod);
        }

        // --- 5. AMBIL DATA TABEL (PAGINASI) ---
        $orders = $baseQuery->with(['customer', 'cashier'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('livewire.karyawan.pos.pos-management', [
            'orders' => $orders,
            'stats' => $stats // Kirim data statistik ke view
        ]);
    }
}

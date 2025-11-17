<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')] // Menggunakan layout Stisla/Admin Anda
class ProductionList extends Component
{
    // Properti untuk menyimpan hasil
    public $processingList;

    public function mount()
    {
        $this->loadProductionList();
    }

    /**
     * Query ini adalah inti dari fitur Anda.
     * Ini menjumlahkan semua item dari order PO yang LUNAS dan PERLU DIBUAT.
     */
    public function loadProductionList()
    {
        $this->processingList = OrderItem::query()
            // 1. Gabungkan dengan tabel 'orders'
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            // 2. Gabungkan dengan tabel 'products' untuk dapat nama
            ->join('products', 'order_items.product_id', '=', 'products.id')

            // 3. Filter HANYA untuk:
            ->where('orders.order_type', 'online')     // (a) Order E-commerce (PO)
            ->where('orders.payment_status', 'paid')   // (b) Yang Lunas
            ->where('orders.status', 'processing') // (c) Yang SEDANG DIPROSES (Perlu Dibuat)

            // 4. Pilih kolom yang kita butuhkan
            ->select(
                'products.name as product_name',
                'products.id as product_id',
                // 5. Jumlahkan total kuantitasnya
                DB::raw('SUM(order_items.jumlah) as total_quantity_needed')
            )

            // 6. Kelompokkan berdasarkan produk
            ->groupBy('products.name', 'products.id')

            // 7. Urutkan dari yang paling banyak dipesan
            ->orderBy('total_quantity_needed', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.karyawan.production-list');
    }
}

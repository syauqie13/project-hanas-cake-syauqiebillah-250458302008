<?php

namespace App\Livewire\Front;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Models\Product; // Pastikan Model Product di-import

#[Layout('components.layouts.front')]

class Konten extends Component
{
    public function render()
    {
        // $today = Carbon::today(); // Tidak dipakai lagi

        // Ambil 5 produk terlaris berdasarkan OrderItem bulan ini
        $topProducts = OrderItem::select('product_id', DB::raw('sum(jumlah) as total_qty'))
            // Ubah query dari whereDate menjadi whereMonth dan whereYear
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->with('product') // Eager load relasi 'product'
            ->get();

        // Kirim data 'topProducts' ke view dengan nama variabel 'products'
        return view('livewire.front.konten', [
            'products' => $topProducts,
        ]);
    }
}

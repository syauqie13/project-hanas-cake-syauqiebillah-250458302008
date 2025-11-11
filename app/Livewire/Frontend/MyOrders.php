<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth; // Untuk mengambil ID user yang login
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')] // Menggunakan layout e-commerce
#[Title('Pesanan Saya')]
class MyOrders extends Component
{
    use WithPagination;

    public function render()
    {
        // 1. Ambil semua order
        $orders = Order::where('user_id', Auth::id()) // <-- HANYA untuk user yang login
            ->where('order_type', 'online') // <-- HANYA order e-commerce (PO)
            ->with('items.product') // Load relasi items & produknya
            ->latest() // Tampilkan yang terbaru di atas
            ->paginate(10); // Paginasi 10 order per halaman

        return view('livewire.frontend.my-orders', [
            'orders' => $orders
        ]);
    }
}

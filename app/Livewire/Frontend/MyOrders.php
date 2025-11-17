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
        $orders = Order::where('user_id', Auth::id())
            ->where('order_type', 'online')
            ->with('items.product')
            ->latest() // Urutkan dari yang terbaru
            ->take(30)
            ->paginate(10);

        return view('livewire.frontend.my-orders', [
            'orders' => $orders
        ]);
    }
}

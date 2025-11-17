<?php

namespace App\Livewire\Atom;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory; // <-- 1. IMPORT MODEL INVENTORY
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $search = '';

    public function performSearch()
    {
        if (empty($this->search)) {
            return;
        }

        if (!Auth::check()) {
            return;
        }

        $query = (string) $this->search;
        $userRole = Auth::user()->role;

        // PRIORITAS 1 — Cek Order
        if (is_numeric($query)) {
            $order = Order::where('id', $query)->exists();
        } else {
            $order = Order::where('merchant_order_id', $query)->exists();
        }

        if ($order) {
            return $this->redirect(route('karyawan.orders.list', ['search' => $query]), navigate: true);
        }

        // PRIORITAS 2 — Produk
        if (Product::where('name', 'like', "%{$query}%")->exists()) {
            return $this->redirect(route($userRole . '.list-product', ['search' => $query]), navigate: true);
        }

        // PRIORITAS 3 — Inventory khusus karyawan
        if ($userRole === 'karyawan' && Inventory::where('name', 'like', "%{$query}%")->exists()) {
            return $this->redirect(route('karyawan.list-inventory', ['search' => $query]), navigate: true);
        }

        // FALLBACK
        return $this->redirect(route($userRole . '.list-product', ['search' => $query]), navigate: true);
    }


    public function render()
    {
        return view('livewire.atom.navbar');
    }
}

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

    // --- PROPERTI UNTUK NOTIFIKASI ---
    public $notifications = [];
    public $notifCount = 0;

    /**
     * Mount: Load notifikasi saat komponen pertama kali dimuat.
     */
    public function mount()
    {
        $this->loadNotifications();
    }

    /**
     * Fungsi untuk mengambil data stok menipis
     */
    public function loadNotifications()
    {
        $lowStockProducts = Product::where('stock', '<', 10) // Ambang batas 10 pcs
            ->where('stock', '>', 0)
            ->get();

        $lowStockInventories = Inventory::where('type', 'bahan_baku')
            ->where('stock', '<', 1000) // Ambang batas 1000 gram/ml
            ->where('stock', '>', 0)
            ->get();

        $notifs = [];

        // Notifikasi untuk Produk (Kue Jadi)
        foreach ($lowStockProducts as $item) {
            $notifs[] = [
                'icon' => 'fas fa-birthday-cake', // Ikon kue
                'color' => 'bg-danger', // Merah
                'message' => "Stok menipis: {$item->name} (Sisa {$item->stock} pcs)",
                'url' => route('karyawan.list-product', ['search' => $item->name]) // Link ke halaman produk
            ];
        }

        // Notifikasi untuk Inventaris (Bahan Baku)
        foreach ($lowStockInventories as $item) {
            $notifs[] = [
                'icon' => 'fas fa-box-open', // Ikon bahan baku
                'color' => 'bg-warning', // Kuning
                'message' => "Bahan baku menipis: {$item->name} (Sisa {$item->stock} {$item->unit})",
                'url' => route('karyawan.list-inventory', ['search' => $item->name]) // Link ke halaman inventory
            ];
        }

        $this->notifications = $notifs;
        $this->notifCount = count($notifs);
    }
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

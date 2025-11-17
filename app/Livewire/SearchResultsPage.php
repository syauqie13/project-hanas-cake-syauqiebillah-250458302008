<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('components.layouts.app')] // Menggunakan layout utama Anda
class SearchResultsPage extends Component
{
    /**
     * #[Url] akan otomatis mengambil 'query' dari URL
     * (contoh: /search?query=kue) dan memasukkannya
     * ke properti $query ini.
     */
    #[Url(keep: true)]
    public $query = '';

    public function render()
    {
        $products = collect();
        $orders = collect();
        $customers = collect();
        $inventories = collect();

        // Hanya jalankan pencarian jika query tidak kosong
        if (strlen($this->query) > 2) { // Minimal 3 huruf

            $searchTerm = '%' . $this->query . '%';

            // Cari di Produk
            $products = Product::where('name', 'like', $searchTerm)->get();

            // Cari di Inventaris
            $inventories = Inventory::where('name', 'like', $searchTerm)->get();

            // Cari di Pesanan (berdasarkan ID atau nama pengiriman)
            $orders = Order::where('id', 'like', $searchTerm)
                ->orWhere('shipping_name', 'like', $searchTerm)
                ->orWhere('merchant_order_id', 'like', $searchTerm)
                ->orWhere('shipping_phone', 'like', $searchTerm)
                ->get();

            // Cari di Pelanggan
            $customers = Customer::where('name', 'like', $searchTerm)
                ->orWhere('phone', 'like', $searchTerm)
                ->get();
        }

        return view('livewire.search-results-page', [
            'products' => $products,
            'orders' => $orders,
            'customers' => $customers,
            'inventories' => $inventories,
        ]);
    }
}

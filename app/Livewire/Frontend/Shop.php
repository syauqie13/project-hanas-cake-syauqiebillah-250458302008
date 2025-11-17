<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category; // 1. Tambahkan Model Category
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination; // 2. Tambahkan WithPagination

#[Layout('components.layouts.ecommerce')]
#[Title('Selamat Datang di Toko PO Kami')]
class Shop extends Component
{
    use WithPagination; // 3. Gunakan Trait Pagination

    // ===================================
    // === 4. PROPERTI BARU DITAMBAHKAN ===
    // ===================================
    public $search = '';
    public $selectedCategory = null; // atau ''
    // ===================================

    /**
     * (Fungsi 'addToCart' Anda yang sudah ada)
     */
    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->is_po || $product->po_deadline->isPast() || $product->stock <= 0) {
            $this->dispatch('notify', [
                'message' => 'Produk ini sudah tidak tersedia untuk PO.',
                'icon' => 'error'
            ]);
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price - ($product->price * $product->discount / 100),
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('notify', [
            'message' => $product->name . ' berhasil ditambahkan ke keranjang!',
            'icon' => 'success'
        ]);

        $this->dispatch('cartUpdated');
    }

    /**
     * PERBAIKAN: Reset paginasi saat 'search' atau 'selectedCategory' berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_po', true)
            ->where('po_deadline', '>', now())
            ->where('stock', '>', 0)

            // ===================================
            // === 5. QUERY FILTER DITAMBAHKAN ===
            // ===================================
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            // ===================================

            ->latest('po_deadline')
            ->paginate(12); // Pastikan menggunakan paginate

        // ===================================
        // === 6. AMBIL DATA KATEGORI ===
        // ===================================
        $categories = Category::orderBy('name')->get();

        return view('livewire.frontend.shop', [
            'products' => $products,
            'categories' => $categories // Kirim kategori ke view
        ]);
    }
}

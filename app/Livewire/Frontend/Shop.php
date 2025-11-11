<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Hana Cake - Pre-Order Shop')]
class Shop extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;

    // Hook untuk mereset paginasi saat filter/search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    /**
     * Aksi untuk menambahkan produk ke keranjang (Session)
     */
    public function addToCart($productId)
    {
        $product = Product::where('id', $productId)
            ->where('is_po', true) // Hanya PO
            ->where('po_deadline', '>', now()) // Hanya PO yang masih buka
            ->first();

        if (!$product) {
            $this->dispatch('notify', ['message' => 'Produk tidak valid atau PO sudah ditutup.', 'icon' => 'error']);
            return;
        }

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Cek apakah produk sudah ada di keranjang
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++; // Tambah jumlahnya
        } else {
            // Tambahkan sebagai item baru
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price - ($product->price * $product->discount / 100),
                'image' => $product->image,
                'quantity' => 1,
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        // Kirim event ke layout untuk update ikon keranjang
        $this->dispatch('cartUpdated');

        // Kirim notifikasi sukses
        $this->dispatch('notify', ['message' => 'Produk ditambahkan ke keranjang!', 'icon' => 'success']);
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }



    public function render()
    {
        $products = Product::where('is_po', true) // <-- HANYA PRODUK PO
            ->where('po_deadline', '>', now()) // <-- HANYA PO YANG MASIH BUKA
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest('po_deadline') // Tampilkan yang deadline-nya terdekat (opsional)
            ->paginate(12); // Paginasi 12 produk per halaman

        $categories = Category::all();

        return view('livewire.frontend.shop', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}

<?php

namespace App\Livewire\Shared\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;     // 1. Tambahkan use WithPagination
use Livewire\Attributes\On;       // 2. Tambahkan use On
use Livewire\Attributes\Layout;   // 3. Jaga Layout Anda

#[Layout('components.layouts.app')] // Diambil dari kode Anda
class ProductList extends Component
{
    use WithPagination; // 4. Gunakan trait Pagination

    public $search = ''; // 5. Tambahkan properti untuk search

    // 6. Dengarkan event dari modal Create/Edit
    // Saat produk dibuat atau diupdate, panggil method 'refreshComponent'
    #[On('productCreated')]
    #[On('productUpdated')]
    public function refreshComponent()
    {
        // Reset paginasi jika kita sedang di halaman 2, 3, dst
        // agar bisa melihat item baru di halaman 1
        $this->resetPage();
    }

    // 7. Dengarkan event 'deleteConfirmed' dari Swal
    #[On('deleteConfirmed')]
    public function delete($data) // Method ini menerima array data
    {
        $id = $data['id']; // Ambil ID dari array
        try {
            $product = Product::with('recipes')->findOrFail($id);

            // 8. Logika Hapus yang Benar: Hapus resepnya dulu
            $product->recipes()->delete();

            // Baru hapus produknya
            $product->delete();

            // 9. Kirim notifikasi dengan format yang benar
            $this->dispatch('notify', [
                'message' => 'Produk berhasil dihapus.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            // 10. Penanganan error jika produk terikat (misal: sudah ada di order)
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $this->dispatch('notify', [
                    'message' => 'Gagal menghapus! Produk ini sudah ada di dalam transaksi.',
                    'icon' => 'error'
                ]);
            } else {
                $this->dispatch('notify', [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'icon' => 'error'
                ]);
            }
        }
    }

    // 11. Method 'create' Anda sudah benar (dispatch event)
    public function create()
    {
        $this->dispatch('openCreateModal');
    }

    // 12. Method 'edit' Anda sudah benar (dispatch event)
    public function edit($id)
    {
        $this->dispatch('openEditModal', id: $id);
    }

    // 13. Method 'deleteConfirm' Anda sudah benar (dispatch event ke Swal)
    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    // 14. Render method yang Diperbarui (menggunakan Pagination)
    public function render()
    {
        $products = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%') // Tambahkan search
            ->latest() // Ambil dari kode Anda
            ->paginate(10); // Gunakan PAGINATE, bukan get()

        return view('livewire.shared.product.product-list', [
            'products' => $products, // Kirim data yang sudah dipaginasi
        ]);
    }
}

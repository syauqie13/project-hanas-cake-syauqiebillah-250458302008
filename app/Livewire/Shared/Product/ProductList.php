<?php

namespace App\Livewire\Shared\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use App\Imports\ProductsImport;
use Livewire\Attributes\Locked;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;     // 1. Tambahkan use
use Livewire\Attributes\Layout;   // 3. Jaga Layout Anda
use Livewire\Attributes\On;       // 2. Tambahkan use On
use App\Exports\ProductsExport; // Asumsi kamu sudah buat exportnya

#[Layout('components.layouts.app')] // Diambil dari kode Anda
class ProductList extends Component
{
    use WithPagination; // 4. Gunakan trait Pagination
    use WithFileUploads;

    // --- PROPERTI ---

    #[Url(keep: true, as: 'search')]
    public $search = '';

    #[Locked]
    public $showProductListImportModal = false;

    #[Locked]
    public $fileImport;

    public function export()
    {
        // Download langsung
        return Excel::download(new ProductsExport, 'produk_hanas_cake.xlsx');
    }

    // --- FITUR IMPORT ---
    public function openProductListImportModal()
    {
        $this->reset('fileImport'); // Kosongkan input file tiap buka modal
        $this->resetErrorBag();
        $this->showProductListImportModal = true;
    }

    public function closeProductListImportModal()
    {
        $this->showProductListImportModal = false;
    }

    public function import()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $this->fileImport);

            session()->flash('success', 'Import Berhasil!');
            $this->showProductListImportModal = false; // Tutup modal

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }
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

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        // Handle jika $id dikirim sebagai array ['id' => 1]
        if (is_array($id)) {
            $id = $id['id'];
        }

        try {
            $product = Product::with('recipes')->findOrFail($id);

            // A. Hapus Resep Terkait
            // (PENTING: agar tidak error 'foreign key')
            $product->recipes()->delete();

            // B. Hapus Gambar Terkait
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // C. Hapus Produk
            $product->delete();

            $this->dispatch('notify', [
                'message' => 'Produk dan semua resepnya berhasil dihapus.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            // Penanganan error jika produk terikat (misal: sudah ada di OrderItem)
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

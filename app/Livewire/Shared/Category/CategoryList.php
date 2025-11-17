<?php

namespace App\Livewire\Shared\Category;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Category;
use Livewire\WithPagination; // 1. Tambahkan ini untuk paginasi

class CategoryList extends Component
{
    use WithPagination; // 2. Gunakan trait paginasi

    // $categories dihapus, karena data akan diambil di render()
    public $search = '';

    // 3. Hapus $listeners (kita akan gunakan #[On] di bawah)

    // 4. Perbaiki listener:
    //    Dengarkan event dan panggil method 'refreshComponent'
    #[On('categoryCreated')]
    #[On('categoryUpdated')]
    public function refreshComponent()
    {
        // Reset paginasi ke halaman 1
        // Ini memastikan Anda melihat item baru jika Anda sedang di halaman 2, 3, dst.
        $this->resetPage();
    }

    // 5. Tambahkan hook 'updatingSearch'
    //    Ini otomatis berjalan saat $search diubah
    //    dan mereset paginasi ke halaman 1
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // 6. Hapus mount() dan loadCategories()
    //    Logika ini sekarang ada di dalam render()

    // 🔹 Buka modal create
    public function create()
    {
        $this->dispatch('openCreateModal');
    }

    // 🔹 Buka modal edit
    public function edit($id)
    {
        $this->dispatch('openEditModal', id: $id);
    }

    // 🔹 Konfirmasi hapus (trigger ke JS alert)
    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    // 🔹 Eksekusi Hapus (Setelah dikonfirmasi oleh SweetAlert)
    #[On('deleteConfirmed')]
    public function delete($id)
    {
        // Handle jika $id dikirim sebagai array ['id' => 1] (keamanan ekstra)
        if (is_array($id)) {
            $id = $id['id'];
        }

        try {
            $category = Category::findOrFail($id); // Sekarang $id sudah benar
            $category->delete();

            // Kirim notifikasi ke frontend
            $this->dispatch('notify', ['message' => 'Kategori berhasil dihapus.']);

        } catch (\Exception $e) {
            // Penanganan error jika kategori terikat (masih ada produk)
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $this->dispatch('notify', [
                    'message' => 'Gagal menghapus! Kategori ini masih digunakan oleh produk.',
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

    // 8. Perbarui render() untuk mengambil data
    public function render()
    {
        // Ambil data di sini, bukan di mount()
        $categories = Category::withCount('products') // <-- PERUBAHAN DI SINI
            ->where('name', 'like', '%' . $this->search . '%') // Tambahkan pencarian
            ->latest()
            ->paginate(10); // Ubah get() menjadi paginate()

        return view('livewire.shared.category.category-list', [
            'categories' => $categories // Kirim data paginasi ke view
        ]);
    }
}

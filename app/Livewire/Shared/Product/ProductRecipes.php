<?php

namespace App\Livewire\Shared\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductRecipe;
use Livewire\Attributes\On;

class ProductRecipes extends Component
{
    public $isOpen = false;
    public $product;
    public $currentRecipes = [];
    public $allInventories = [];

    // Properti untuk form 'Tambah Resep'
    public $inventory_id;
    public $quantity_used;

    protected $rules = [
        'inventory_id' => 'required|exists:inventories,id',
        'quantity_used' => 'required|numeric|min:0.01',
    ];

    #[On('openRecipeModal')]
    public function openRecipeModal($id)
    {
        $this->product = Product::findOrFail($id);
        $this->loadCurrentRecipes();

        // Ambil HANYA bahan baku
        $this->allInventories = Inventory::where('type', 'bahan_baku')->orderBy('name')->get();

        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function loadCurrentRecipes()
    {
        // Eager load nama bahan baku dari tabel 'inventories'
        $this->currentRecipes = $this->product->recipes()->with('inventory')->get();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->inventory_id = '';
        $this->quantity_used = '';
        $this->resetErrorBag();
    }

    public function addRecipe()
    {
        $this->validate();

        // Cek jika bahan baku ini sudah ada di resep
        $existing = $this->product->recipes()->where('inventory_id', $this->inventory_id)->first();
        if ($existing) {
            // Jika sudah ada, update saja jumlahnya
            $existing->update(['quantity_used' => $this->quantity_used]);
        } else {
            // Jika belum, buat resep baru
            $this->product->recipes()->create([
                'inventory_id' => $this->inventory_id,
                'quantity_used' => $this->quantity_used,
            ]);
        }

        $this->dispatch('notify', ['message' => 'Resep berhasil ditambahkan/diperbarui.']);
        $this->loadCurrentRecipes(); // Muat ulang daftar resep
        $this->resetInputFields(); // Kosongkan form
    }

    public function removeRecipe($recipeId)
    {
        try {
            ProductRecipe::findOrFail($recipeId)->delete();
            $this->loadCurrentRecipes(); // Muat ulang daftar resep
            $this->dispatch('notify', ['message' => 'Bahan baku berhasil dihapus dari resep.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Gagal menghapus resep.', 'icon' => 'error']);
        }
    }

    // Biarkan render kosong, karena ini hanya modal
    public function render()
    {
        return view('livewire.shared.product.product-recipes');
    }
}

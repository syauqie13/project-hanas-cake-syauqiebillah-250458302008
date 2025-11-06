<?php

namespace App\Livewire\Shared\Inventories;

use Livewire\Component;
use App\Models\Inventory;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class InventoryList extends Component
{
    use WithPagination;

    // Properti untuk modal & form
    public $isOpen = false;
    public $inventoryId;

    // Properti sesuai skema
    public $name, $stock, $unit, $type, $unit_price, $description;

    // Properti untuk filter/search
    public $search = '';

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('inventories')->ignore($this->inventoryId)
            ],
            // PERUBAHAN DI SINI: Menggunakan satuan terkecil
            'type' => 'required|in:bahan_baku,produk_jadi',
            'unit' => 'required|in:gram,ml,pcs,pack,box', // <-- BARIS DIPERBARUI

            // Stok sekarang adalah angka bulat (integer)
            'stock' => 'required|numeric|min:0', // <-- Tipe 'decimal' di DB, tapi input kita angka bulat
            'unit_price' => 'required|numeric|min:0', // Harga modal tetap boleh desimal
            'description' => 'nullable|string',
        ];
    }

    // Pesan validasi kustom
    protected $messages = [
        'type.in' => 'Tipe yang dipilih tidak valid.',
        'unit.in' => 'Satuan yang dipilih tidak valid. (Pilih: gram, ml, pcs, pack, box)',
    ];

    // Listener untuk event 'deleteConfirmed' dari JavaScript
    #[On('deleteConfirmed')]
    public function deleteConfirmed($data)
    {
        $id = $data['id'];
        try {
            $inventory = Inventory::with('recipes')->findOrFail($id);
            if ($inventory->recipes->isNotEmpty()) {
                $this->dispatch('notify', [
                    'message' => 'Gagal menghapus! Bahan baku ini sedang digunakan di dalam resep produk.',
                    'icon' => 'error'
                ]);
                return;
            }
            $inventory->delete();
            $this->dispatch('notify', [
                'message' => 'Bahan baku berhasil dihapus.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        $inventories = Inventory::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.shared.inventories.inventory-list', [
            'inventories' => $inventories,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->inventoryId = null;
        $this->name = '';
        $this->stock = '0';
        $this->unit = '';
        $this->type = '';
        $this->unit_price = '0';
        $this->description = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Inventory::updateOrCreate(['id' => $this->inventoryId], [
            'name' => $this->name,
            'type' => $this->type,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'unit_price' => $this->unit_price,
            'description' => $this->description,
        ]);

        $this->dispatch('notify', [
            'message' => $this->inventoryId ? 'Bahan baku berhasil diperbarui.' : 'Bahan baku berhasil ditambahkan.',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $this->inventoryId = $id;
        $this->name = $inventory->name;
        $this->stock = $inventory->stock;
        $this->unit = $inventory->unit;
        $this->type = $inventory->type;
        $this->unit_price = $inventory->unit_price;
        $this->description = $inventory->description;

        $this->resetErrorBag();
        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', ['id' => $id]);
    }
}

<?php

namespace App\Livewire\Shared\Inventories;

use Livewire\Component;
use App\Models\Inventory;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use App\Exports\InventoryExport;
use App\Imports\InventoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
class InventoryList extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // --- PROPERTI ---

    // PERBAIKAN: #[Url] sekarang menempel di properti $search
    #[Url(keep: true, as: 'search')]
    public $search = '';

    // Modal Import
    #[Locked]
    public $showInventoryListImportModal = false;
    #[Locked]
    public $fileImport;

    // Modal Create/Edit (Logika Anda sudah benar)
    #[Locked]
    public $isOpen = false;
    public $inventoryId, $name, $stock, $unit, $type, $unit_price, $description;

    // --- EXPORT ---
    public function export()
    {
        return Excel::download(new InventoryExport, 'inventory_hanas_cake.xlsx');
    }

    // --- IMPORT ---
    public function openImportModal()
    {
        $this->reset('fileImport');
        $this->resetErrorBag();
        $this->showInventoryListImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showInventoryListImportModal = false;
    }

    public function import()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new InventoryImport, $this->fileImport);
            session()->flash('success', 'Import berhasil!');
            $this->showInventoryListImportModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // --- EVENT LISTENERS ---
    #[On('inventoryCreated')]
    #[On('inventoryUpdated')]
    public function refreshComponent()
    {
        $this->resetPage();
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        if (is_array($id))
            $id = $id['id'];

        try {
            $inventory = Inventory::findOrFail($id);

            // Cek relasi ke resep
            if ($inventory->recipes()->exists()) {
                $this->dispatch('notify', [
                    'message' => 'Gagal! Bahan baku ini sedang dipakai di Resep Produk.',
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
            if ($e->getCode() == '23000') {
                $this->dispatch('notify', [
                    'message' => 'Gagal! Data ini terkunci relasi database.',
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

    // --- CREATE / EDIT / DELETE ---
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
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
        $this->dispatch('confirmDelete', id: $id);
    }

    public function store()
    {
        $this->validate($this->rules());

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
        $this->refreshComponent();
    }

    // --- MODAL HELPERS ---
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
        $this->stock = 0;
        $this->unit = '';
        $this->type = '';
        $this->unit_price = 0;
        $this->description = '';
        $this->resetErrorBag();
    }

    // --- VALIDATION ---
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('inventories')->ignore($this->inventoryId)],
            'type' => 'required|in:bahan_baku,produk_jadi',
            'unit' => 'required|in:gram,ml,pcs,pack,box',
            'stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    protected $messages = [
        'type.in' => 'Tipe yang dipilih tidak valid.',
        'unit.in' => 'Satuan yang dipilih tidak valid. (Pilih: gram, ml, pcs, pack, box)',
    ];

    // --- RENDER ---
    public function render()
    {
        $inventories = Inventory::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.shared.inventories.inventory-list', [
            'inventories' => $inventories,
        ]);
    }
}

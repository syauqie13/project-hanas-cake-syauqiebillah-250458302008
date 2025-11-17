<?php

namespace App\Livewire\Karyawan\Shipping;

use Livewire\Component;
use App\Models\ShippingZone;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class ZoneManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;

    public $zoneId;
    public $name;
    public $price;
    public $requires_confirmation = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'requires_confirmation' => 'boolean',
    ];

    public function render()
    {
        $zones = ShippingZone::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.karyawan.shipping.zone-management', [
            'zones' => $zones
        ]);
    }

    // ... (create, openModal, closeModal, resetInputFields, store, edit - TETAP SAMA) ...
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }
    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('show-zone-modal');
    }
    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('hide-zone-modal');
    }
    private function resetInputFields()
    {
        $this->zoneId = null;
        $this->name = '';
        $this->price = 0;
        $this->requires_confirmation = false;
        $this->resetErrorBag();
    }
    public function store()
    {
        $this->validate();
        ShippingZone::updateOrCreate(['id' => $this->zoneId], [
            'name' => $this->name,
            'price' => $this->price,
            'requires_confirmation' => $this->requires_confirmation,
        ]);
        $this->dispatch('notify', ['message' => $this->zoneId ? 'Zona berhasil diperbarui.' : 'Zona berhasil ditambahkan.', 'icon' => 'success']);
        $this->closeModal();
        $this->resetInputFields();
    }
    public function edit($id)
    {
        $zone = ShippingZone::findOrFail($id);
        $this->zoneId = $id;
        $this->name = $zone->name;
        $this->price = $zone->price;
        $this->requires_confirmation = $zone->requires_confirmation;
        $this->openModal();
    }

    // Fungsi Pemicu Konfirmasi
    public function deleteConfirm($id)
    {
        // Kirim ID langsung ke JS
        $this->dispatch('confirmDelete', id: $id);
    }

    // Fungsi Penghapus (Listener)
    #[On('deleteConfirmed')]
    public function delete($id)
    {
        // PERBAIKAN: Handle jika $id dikirim sebagai array ['id' => 1]
        if (is_array($id)) {
            $id = $id['id'];
        }

        try {
            ShippingZone::findOrFail($id)->delete();
            $this->dispatch('notify', ['message' => 'Zona berhasil dihapus.', 'icon' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Gagal menghapus: ' . $e->getMessage(), 'icon' => 'error']);
        }
    }
}

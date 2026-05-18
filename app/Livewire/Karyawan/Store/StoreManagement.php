<?php

namespace App\Livewire\Karyawan\Store;

use App\Models\Store;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')] // Diambil dari kode Anda
class StoreManagement extends Component
{

    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $storeId;
    public $name;
    public $address;
    public $latitude;
    public $longitude;
    public $open_time;
    public $close_time;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'open_time' => 'nullable|date_format:H:i',
        'close_time' => 'nullable|date_format:H:i|after:open_time',
        'is_active' => 'boolean',
    ];


    public function render()
    {
        $stores = Store::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.karyawan.store.store-management', [
            'stores' => $stores
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
        $this->dispatch('show-store-modal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('hide-store-modal');
    }

    public function resetInputFields()
    {
        $this->storeId = null;
        $this->name = '';
        $this->address = '';
        $this->latitude = null;
        $this->longitude = null;
        $this->open_time = null;
        $this->close_time = null;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Store::updateOrCreate(
            ['id' => $this->storeId],
            [
                'name' => $this->name,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'open_time' => $this->open_time,
                'close_time' => $this->close_time,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('notify', ['message' => $this->storeId ? 'Store updated successfully.' : 'Store created successfully.', 'icon' => 'success']);

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $this->storeId = $store->id;
        $this->name = $store->name;
        $this->address = $store->address;
        $this->latitude = $store->latitude;
        $this->longitude = $store->longitude;
        $this->open_time = $store->open_time ? date('H:i', strtotime($store->open_time)) : null;
        $this->close_time = $store->close_time ? date('H:i', strtotime($store->close_time)) : null;
        $this->is_active = $store->is_active;

        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        try {
            Store::findOrFail($id)->delete();
            $this->dispatch('notify', ['message' => 'Store deleted successfully.', 'icon' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Failed to delete store: ' . $e->getMessage(), 'icon' => 'error']);
        }
    }

}

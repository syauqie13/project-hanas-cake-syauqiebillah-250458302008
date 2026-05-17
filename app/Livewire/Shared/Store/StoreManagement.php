<?php

namespace App\Livewire\Shared\Store;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Store;

class StoreManagement extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Store::find($id)->delete();
        $this->dispatch('notify', ['message' => 'Store berhasil dihapus', 'icon' => 'success']);
    }

    public function render()
    {
        return view('livewire.shared.store.store-management', [
            'stores' => Store::paginate(10)
        ])->layout('components.layouts.app');
    }
}

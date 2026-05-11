<?php

namespace App\Livewire\Karyawan\Voucher;

use Livewire\Component;
use App\Models\Voucher;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class VoucherManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;

    public $voucherId;
    public $code;
    public $type = 'nominal';
    public $value;
    public $min_purchase;
    public $max_discount;
    public $valid_until;
    public $is_active = true;

    protected $rules = [
        'code' => 'required|string|max:255',
        'type' => 'required|in:nominal,percentage',
        'value' => 'required|numeric|min:1',
        'min_purchase' => 'nullable|numeric|min:0',
        'max_discount' => 'nullable|numeric|min:0',
        'valid_until' => 'nullable|date',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $vouchers = Voucher::where('code', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.karyawan.voucher.voucher-management', [
            'vouchers' => $vouchers
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
        $this->dispatch('show-voucher-modal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('hide-voucher-modal');
    }

    private function resetInputFields()
    {
        $this->voucherId = null;
        $this->code = '';
        $this->type = 'nominal';
        $this->value = null;
        $this->min_purchase = null;
        $this->max_discount = null;
        $this->valid_until = null;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();
        
        // Pastikan kode unik selain yang sedang diedit
        if (Voucher::where('code', $this->code)->where('id', '!=', $this->voucherId)->exists()) {
            $this->addError('code', 'Kode voucher sudah digunakan.');
            return;
        }

        Voucher::updateOrCreate(['id' => $this->voucherId], [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase ?: null,
            'max_discount' => $this->type == 'percentage' ? ($this->max_discount ?: null) : null,
            'valid_until' => $this->valid_until ?: null,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('notify', ['message' => $this->voucherId ? 'Voucher berhasil diperbarui.' : 'Voucher berhasil ditambahkan.', 'icon' => 'success']);
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $this->voucherId = $id;
        $this->code = $voucher->code;
        $this->type = $voucher->type;
        $this->value = $voucher->value;
        $this->min_purchase = $voucher->min_purchase;
        $this->max_discount = $voucher->max_discount;
        $this->valid_until = $voucher->valid_until;
        $this->is_active = $voucher->is_active;
        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        if (is_array($id)) {
            $id = $id['id'];
        }

        try {
            Voucher::findOrFail($id)->delete();
            $this->dispatch('notify', ['message' => 'Voucher berhasil dihapus.', 'icon' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Gagal menghapus: ' . $e->getMessage(), 'icon' => 'error']);
        }
    }
}

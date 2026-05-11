<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert; // Opsional jika pakai library alert

class UpdatePin extends Component
{
    public $current_pin = '';
    public $new_pin = '';
    public $new_pin_confirmation = '';

    public function updatePin()
    {
        $this->validate([
            'current_pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6|confirmed|different:current_pin',
        ], [
            'current_pin.required' => 'PIN lama wajib diisi.',
            'new_pin.different' => 'PIN baru tidak boleh sama dengan PIN lama.',
            'new_pin.confirmed' => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        $user = auth()->user();

        // Verifikasi apakah PIN lama benar
        if (!Hash::check($this->current_pin, $user->payment_pin)) {
            $this->addError('current_pin', 'PIN lama yang Anda masukkan salah.');
            return;
        }

        // Update ke PIN baru
        $user->update([
            'payment_pin' => Hash::make($this->new_pin)
        ]);

        $this->reset(['current_pin', 'new_pin', 'new_pin_confirmation']);
        session()->flash('success', 'PIN Pembayaran berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.frontend.update-pin');
    }
}
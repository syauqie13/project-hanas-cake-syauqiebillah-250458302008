<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
class SetupPin extends Component
{
    public $pin = '';
    public $pin_confirmation = '';
    public $step = 1;
    public $is_reset = false; // Flag untuk menandai apakah ini proses reset

    public function mount()
    {
        // Jika user sudah punya PIN, berarti dia masuk ke sini untuk RESET
        // Kita tandai agar pesannya nanti berbeda
        if (auth()->user()->payment_pin) {
            $this->is_reset = true;
        }
    }

    public function goToStep2()
    {
        $this->validate([
            'pin' => 'required|digits:6',
        ], [
            'pin.required' => 'Silakan masukkan 6 digit PIN.',
            'pin.digits' => 'PIN harus berjumlah 6 digit angka.',
        ]);

        $this->step = 2;
    }

    public function savePin()
    {
        $this->validate([
            'pin' => 'required|digits:6',
            'pin_confirmation' => 'required|same:pin',
        ], [
            'pin_confirmation.required' => 'Konfirmasi PIN wajib diisi.',
            'pin_confirmation.same' => 'Konfirmasi PIN tidak cocok.',
        ]);

        // Simpan PIN baru (akan menimpa yang lama jika ada)
        auth()->user()->update([
            'payment_pin' => Hash::make($this->pin)
        ]);

        // Berikan pesan sukses yang berbeda tergantung kondisi
        $message = $this->is_reset ? 'PIN Pembayaran berhasil direset!' : 'PIN Pembayaran berhasil dibuat!';
        
        session()->flash('success', $message);
        
        return redirect()->route('ecommerce');
    }

    public function render()
    {
        return view('livewire.frontend.setup-pin');
    }
}
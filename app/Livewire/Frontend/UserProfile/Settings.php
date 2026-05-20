<?php

namespace App\Livewire\Frontend\UserProfile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Pengaturan')]
class Settings extends Component
{
    public $step = 'main'; // Mengatur tampilan: main, pin, notif, bahasa

    // State untuk PIN
    public $has_pin;
    public $old_pin;
    public $new_pin;
    public $new_pin_confirmation;

    // State untuk Notifikasi
    public $notif_email;
    public $notif_wa;

    // State untuk Bahasa
    public $locale;

    public function mount()
    {
        $user = Auth::user();
        
        // Cek apakah user sudah punya PIN sebelumnya
        $this->has_pin = !empty($user->payment_pin);
        
        $this->notif_email = $user->notif_email;
        $this->notif_wa = $user->notif_wa;
        $this->locale = $user->locale ?? 'id';
    }

    public function setStep($stepName)
    {
        $this->step = $stepName;
        $this->resetValidation(); // Bersihkan error jika pindah menu
    }

    public function updatePin()
    {
        $user = Auth::user();

        // Jika sudah punya PIN, wajib masukkan PIN lama
        if ($this->has_pin) {
            $this->validate([
                'old_pin' => 'required',
                'new_pin' => 'required|numeric|digits:6|confirmed',
            ], [
                'new_pin.confirmed' => 'Konfirmasi PIN baru tidak cocok.',
                'new_pin.digits' => 'PIN harus terdiri dari 6 angka.'
            ]);

            if (!Hash::check($this->old_pin, $user->payment_pin)) {
                $this->addError('old_pin', 'PIN lama tidak sesuai.');
                return;
            }
        } else {
            // Jika belum punya PIN, langsung buat baru
            $this->validate([
                'new_pin' => 'required|numeric|digits:6|confirmed',
            ]);
        }

        // Simpan PIN baru yang sudah di-hash demi keamanan
        $user->update([
            'payment_pin' => Hash::make($this->new_pin)
        ]);

        $this->has_pin = true;
        $this->reset(['old_pin', 'new_pin', 'new_pin_confirmation']);
        $this->step = 'main';
        $this->dispatch('notify', ['message' => 'PIN berhasil diperbarui!', 'icon' => 'success']);
    }

    public function updateNotif()
    {
        Auth::user()->update([
            'notif_email' => $this->notif_email,
            'notif_wa' => $this->notif_wa,
        ]);

        $this->step = 'main';
        $this->dispatch('notify', ['message' => 'Pengaturan notifikasi disimpan!', 'icon' => 'success']);
    }

    public function updateLanguage()
    {
        Auth::user()->update(['locale' => $this->locale]);
        
        // Simpan ke session agar bisa dibaca oleh middleware bahasa Laravel
        session()->put('locale', $this->locale); 
        app()->setLocale($this->locale);

        $this->step = 'main';
        
        // Simpan notifikasi ke session flash agar muncul setelah refresh
        session()->flash('notify_success', 'Bahasa berhasil diubah!');
        
        $this->redirect(route('pelanggan.profile.settings'), navigate: false);
    }

    public function render()
    {
        return view('livewire.frontend.user-profile.settings');
    }
}
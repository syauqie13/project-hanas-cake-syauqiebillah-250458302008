<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomerLogin extends Component
{

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Arahkan kembali ke halaman toko
        return $this->redirect(route('login'), navigate: true);
    }
    public function render()
    {
        return view('livewire.frontend.auth.customer-login');
    }
}

<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{

    protected $listeners = ['execute-logout' => 'logout'];
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirect(route('front'));
    }
    public function render()
    {
        return view('livewire.auth.logout');
    }
}

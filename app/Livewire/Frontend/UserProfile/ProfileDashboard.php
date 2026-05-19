<?php

namespace App\Livewire\Frontend\UserProfile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Profil Saya')]
class ProfileDashboard extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Redirect ke halaman depan
        return $this->redirect(route('front'), navigate: true);
    }
    public function render()
    {
        return view('livewire.frontend.user-profile.profile-dashboard', [
            'user' => Auth::user()
        ]);
    }
}
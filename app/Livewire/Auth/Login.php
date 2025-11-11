<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|email', message: [
        'required' => 'Email wajib diisi.',
        'email' => 'Format email tidak valid.',
    ])]
    public $email = '';

    #[Validate('required', message: 'Password wajib diisi.')]
    public $password = '';

    public $remember = false;

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        // dd($this->email, $this->password, $this->remember);
        $this->validate();

        if (
            Auth::attempt(
                ['email' => $this->email, 'password' => $this->password],
                $this->remember
            )
        ) {
            $user = Auth::user();

            // 🔁 Redirect sesuai role
            return match ($user->role) {
                'admin' => $this->redirect(route('admin.dashboard'), navigate: true),
                'karyawan' => $this->redirect(route('karyawan.dashboard'), navigate: true),
                'pelanggan' => $this->redirect(route('ecommerce'), navigate: true),
                default => $this->redirect('/', navigate: true),
            };
        }

        // ❌ Jika gagal login
        session()->flash('error', 'Email atau password salah.');
    }
}

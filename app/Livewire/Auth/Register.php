<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Customer; // <-- 1. TAMBAHKAN IMPORT INI
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validasi input dengan pesan custom
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email ini sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );

        // Hash password & set role
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pelanggan';

        // Buat user baru
        $user = User::create($validated);

        // Jika role pelanggan, buat data customer juga
        if ($user->role === 'pelanggan') {
            Customer::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        }

        // Event Laravel default (email verification dsb)
        event(new Registered($user));

        // Login otomatis
        Auth::login($user);

        // Redirect ke halaman pemberitahuan verifikasi email
        $this->redirect(route('verification.notice'), navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.auth.register');
    }
}

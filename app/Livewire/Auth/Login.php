<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')] // Asumsi Anda punya layout 'guest' (tanpa sidebar/navbar)
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    /**
     * Aturan validasi dasar
     */
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    /**
     * Pesan validasi kustom
     */
    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ];

    /**
     * Fungsi yang dipanggil saat tombol login ditekan
     */
    public function login()
    {
        $this->validate();

        // Coba autentikasi pengguna
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {

            // 1. Sukses Login
            session()->regenerate();

            $user = Auth::user();

            // 2. Arahkan (Redirect) berdasarkan Role
            if ($user->role == 'admin') {
                return $this->redirect(route('admin.dashboard'));
            } elseif ($user->role == 'karyawan') {
                return $this->redirect(route('karyawan.dashboard'));
            } elseif ($user->role == 'pelanggan') {
                return $this->redirect(route('ecommerce'));
            } else {
                // Fallback jika 'pelanggan' mencoba login di sini
                Auth::logout();
                $this->addError('email', 'Anda tidak memiliki hak akses.');
                return;
            }

        } else {
            // 3. GAGAL Login
            // INI ADALAH PESAN "PASSWORD SALAH" YANG ANDA MINTA
            $this->addError('email', 'Email tidak ditemukan atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

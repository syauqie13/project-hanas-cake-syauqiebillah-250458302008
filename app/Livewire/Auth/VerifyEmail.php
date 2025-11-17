<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.guest')] // Gunakan layout polos/guest
class VerifyEmail extends Component
{
    public function mount()
    {
        // Jika user sudah terverifikasi, lempar ke dashboard sesuai role
        if (Auth::user()->hasVerifiedEmail()) {

            // --- PERBAIKAN LOGIKA REDIRECT ---
            $role = Auth::user()->role;
            if ($role === 'admin' || $role === 'karyawan') {
                return redirect()->intended(route($role . '.dashboard'));
            } elseif ($role === 'pelanggan') {
                return redirect()->intended(route('ecommerce')); // <-- Diarahkan ke ecommerce
            }
            // --- AKHIR PERBAIKAN ---
        }
    }

    public function resendVerification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            // Jika user iseng klik padahal sudah verif, redirect saja

            // --- PERBAIKAN LOGIKA REDIRECT ---
            $role = Auth::user()->role;
            if ($role === 'admin' || $role === 'karyawan') {
                return redirect()->intended(route($role . '.dashboard'));
            } elseif ($role === 'pelanggan') {
                return redirect()->intended(route('ecommerce')); // <-- Diarahkan ke ecommerce
            }
            // --- AKHIR PERBAIKAN ---
        }

        // Kirim ulang email verifikasi bawaan Laravel
        Auth::user()->sendEmailVerificationNotification();

        $this->dispatch('notify', [
            'message' => 'Link verifikasi baru telah dikirim ke email Anda!',
            'icon' => 'success'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}

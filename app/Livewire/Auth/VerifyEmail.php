<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.guest')] // Gunakan layout polos/guest
class VerifyEmail extends Component
{
    public $verificationCode = '';

    public function mount()
    {
        // Jika user sudah terverifikasi, lempar ke dashboard sesuai role
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectBasedOnRole();
        }
    }

    public function verifyCode()
    {
        $this->validate([
            'verificationCode' => 'required|string|size:6',
        ], [
            'verificationCode.required' => 'Kode verifikasi wajib diisi.',
            'verificationCode.size' => 'Kode verifikasi harus 6 digit.',
        ]);

        $user = Auth::user();

        if ($user->email_verification_code === $this->verificationCode) {
            // Kode benar, tandai sebagai terverifikasi
            $user->markEmailAsVerified();
            
            // Hapus kode setelah berhasil
            $user->email_verification_code = null;
            $user->save();

            // Redirect sesuai role
            $this->redirectBasedOnRole();
        } else {
            // Kode salah
            $this->addError('verificationCode', 'Kode verifikasi salah atau tidak valid.');
        }
    }

    public function resendVerification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectBasedOnRole();
            return;
        }

        // Kirim ulang email verifikasi (sekarang ini akan memanggil method override kita)
        Auth::user()->sendEmailVerificationNotification();

        $this->dispatch('notify', [
            'message' => 'Kode verifikasi baru telah dikirim ke email Anda!',
            'icon' => 'success'
        ]);
    }

    protected function redirectBasedOnRole()
    {
        $role = Auth::user()->role;
        if ($role === 'admin' || $role === 'karyawan') {
            return redirect()->intended(route($role . '.dashboard'));
        } elseif ($role === 'pelanggan') {
            return redirect()->intended(route('ecommerce')); 
        }
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

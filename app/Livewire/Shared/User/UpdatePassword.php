<?php

namespace App\Livewire\Shared\User;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout; // <-- 1. TAMBAHKAN IMPORT

#[Layout('components.layouts.app')] // <-- 2. TAMBAHKAN INI
class UpdatePassword extends Component
{
    // Properti Form
    public $current_password;
    public $password;
    public $password_confirmation;

    /**
     * Aturan validasi
     */
    protected function rules()
    {
        return [
            'current_password' => [
                'required',
                // Validasi custom untuk mengecek password saat ini
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Password Anda saat ini salah.');
                    }
                },
            ],
            // 'confirmed' akan otomatis mencocokkan dengan 'password_confirmation'
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Pesan error kustom
     */
    protected $messages = [
        'current_password.required' => 'Password saat ini wajib diisi.',
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password baru minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
    ];

    /**
     * Fungsi untuk menyimpan password baru
     */
    public function updatePassword()
    {
        // Jalankan validasi
        $this->validate();

        try {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Hash dan simpan password baru
            $user->password = Hash::make($this->password);
            $user->save();

            // Reset field form
            $this->reset('current_password', 'password', 'password_confirmation');

            // Kirim notifikasi sukses (sesuai sistem notif Anda)
            $this->dispatch('notify', [
                'message' => 'Password berhasil diperbarui.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            // Kirim notifikasi error
            $this->dispatch('notify', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.shared.user.update-password');
    }
}

<?php

namespace App\Livewire\Shared\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use App\Models\Order; // <-- 1. TAMBAHKAN IMPORT INI

#[Layout('components.layouts.app')]
class Profil extends Component
{
    use WithFileUploads;

    public $user;

    // Properti untuk Info Umum
    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $postal_code;

    // Properti untuk Avatar
    public $avatar; // Untuk upload baru
    public $existingAvatar; // Untuk menampilkan yang sudah ada

    // Properti untuk Ganti Password
    public $current_password;
    public $password;
    public $password_confirmation;

    // --- 2. TAMBAHKAN PROPERTI BARU ---
    public $posOrdersHandled = 0;
    // --- AKHIR TAMBAHAN ---


    /**
     * Mount: Dipanggil saat komponen di-load.
     * Mengisi form dengan data user yang sedang login.
     */
    public function mount()
    {
        $this->user = Auth::user();

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
        $this->city = $this->user->city;
        $this->postal_code = $this->user->postal_code;
        $this->existingAvatar = $this->user->avatar;

        // --- 3. TAMBAHKAN LOGIKA INI ---
        // Hitung pencapaian (hanya untuk karyawan)
        if ($this->user->role == 'karyawan') {
            $this->posOrdersHandled = Order::where('cashier_id', $this->user->id)
                ->where('order_type', 'pos')
                ->where('status', 'completed') // Hitung yg selesai saja
                ->count();
        }
        // --- AKHIR TAMBAHAN ---
    }

    /**
     * Fungsi untuk menyimpan perubahan Info Umum & Avatar
     */
    public function updateInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'avatar' => 'nullable|image|max:2048', // Maks 2MB
        ]);

        // 1. Handle Upload Avatar (Jika ada)
        if ($this->avatar) {
            // Hapus avatar lama jika ada
            if ($this->existingAvatar) {
                Storage::disk('public')->delete($this->existingAvatar);
            }
            // Simpan avatar baru
            $path = $this->avatar->store('avatars', 'public');
            $this->user->avatar = $path;
            $this->existingAvatar = $path; // Update tampilan
        }

        // 2. Update data lainnya
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;
        $this->user->address = $this->address;
        $this->user->city = $this->city;
        $this->user->postal_code = $this->postal_code;

        $this->user->save();

        // Kirim notifikasi sukses
        $this->dispatch('notify', message: 'Profil berhasil diperbarui.', icon: 'success');
    }

    /**
     * Fungsi untuk menyimpan Password Baru
     */
    public function updatePassword()
    {
        $this->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user->password)) {
                        $fail('Password saat ini salah.');
                    }
                }
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Update password baru
        $this->user->password = Hash::make($this->password);
        $this->user->save();

        // Kirim notifikasi dan reset field
        $this->dispatch('notify', message: 'Password berhasil diubah.', icon: 'success');
        $this->reset('current_password', 'password', 'password_confirmation');
    }

    public function render()
    {
        return view('livewire.shared.user.profil');
    }
}

<?php

namespace App\Livewire\Frontend\UserProfile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('components.layouts.ecommerce')]
#[Title('Edit Profil Saya')]
class EditProfile extends Component
{
    use WithFileUploads;

    // Properti Form Profil utama sesuai UI baru
    public $name;
    public $email;
    public $phone;
    public $birth_date; // Kolom baru (nullable)
    public $gender;     // Kolom baru (nullable)

    // Properti File Gambar (Avatar)
    public $image;     // Untuk menyimpan file baru yang di-upload temporer
    public $oldImage;  // Untuk menyimpan path avatar lama dari kolom 'avatar' di DB

    // Properti database lama (ditahan agar tidak terjadi error jika layout memanggilnya)
    public $address;
    public $city;
    public $postal_code;

    // Properti Fitur Hapus Akun
    public $deleteReason = '';
    public $otherReason = '';

    public function mount()
    {
        $user = Auth::user();
        
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone; // Sesuai kolom database 'phone'
            $this->birth_date = $user->birth_date; // Mengambil kolom baru lahir
            $this->gender = $user->gender;         // Mengambil kolom baru kelamin
            $this->oldImage = $user->avatar;       // Sesuai nama kolom database Anda yaitu 'avatar'
            
            // Mengamankan data alamat lama
            $this->address = $user->address;
            $this->city = $user->city;
            $this->postal_code = $user->postal_code;
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|numeric|digits_between:10,15',
            'birth_date' => 'nullable|date', // Nullable sesuai struktur tabel baru
            'gender' => 'nullable|string|in:Laki-laki,Perempuan', // Nullable sesuai struktur tabel baru
            'image' => 'nullable|image|max:2048', // Validasi gambar maks 2MB
        ], [
            'name.required' => 'Username wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.numeric' => 'Nomor telepon harus berupa angka murni.',
            'gender.in' => 'Pilihan jenis kelamin tidak sesuai.',
            'image.image' => 'File harus berupa format gambar.',
            'image.max' => 'Ukuran file foto maksimal adalah 2MB.',
        ]);

        // Logika Upload Gambar Avatar
        if ($this->image) {
            // Hapus gambar lama jika eksis di storage public
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }

            // Simpan file baru ke directory public/avatars
            $validated['avatar'] = $this->image->store('avatars', 'public');
        }

        // Unset key 'image' karena kolom fisik pada database Anda bernama 'avatar'
        unset($validated['image']);

        // Update data ke table users
        $user->update($validated);

        // Update state oldImage agar preview langsung berubah mulus tanpa reload halaman
        if (isset($validated['avatar'])) {
            $this->oldImage = $validated['avatar'];
            $this->reset('image'); // Bersihkan input file temp
        }

        $this->dispatch('notify', [
            'message' => 'Profil berhasil diperbarui!',
            'icon' => 'success'
        ]);
    }

    public function deleteAccount()
    {
        // Validasi keamanan: Pastikan alasan hapus telah dipilih
        if (!$this->deleteReason) {
            return;
        }

        if ($this->deleteReason === 'Lainnya' && strlen($this->otherReason) < 3) {
            return;
        }

        $finalReason = $this->deleteReason === 'Lainnya' ? $this->otherReason : $this->deleteReason;

        // Catat alasan penghapusan ke log sistem Laravel (sangat berguna untuk laporan admin/evaluasi)
        \Illuminate\Support\Facades\Log::warning("User ID " . Auth::id() . " (" . Auth::user()->name . ") telah menghapus akun mereka secara permanen. Alasan: " . $finalReason);

        $user = Auth::user();

        // 1. Proses Logout paksa & Amankan Session sebelum data dihancurkan
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // 2. Hapus instans user dari database secara permanen
        $user->delete();

        // 3. Alihkan user kembali ke halaman utama (front) secara bersih dengan wire:navigate
        return $this->redirect(route('front'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.user-profile.edit-profile');
    }
}
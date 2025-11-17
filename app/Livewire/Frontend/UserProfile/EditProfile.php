<?php

namespace App\Livewire\Frontend\UserProfile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- 1. Import Storage
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads; // <-- 2. Import Trait Upload

#[Layout('components.layouts.ecommerce')]
#[Title('Edit Profil Saya')]
class EditProfile extends Component
{
    use WithFileUploads; // <-- 3. Gunakan Trait

    public $name;
    public $email;
    public $phone;
    public $address;

    public $image;    // Untuk file baru yang diupload
    public $oldImage; // Untuk menyimpan path gambar yang ada di DB
    public $city;
    public $postal_code;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->oldImage = $user->avatar; // Isi data avatar lama
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|numeric|digits_between:10,15',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048', // Validasi gambar (max 2MB)
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        // Logika Upload Gambar
        if ($this->image) {
            // 1. Hapus gambar lama jika ada (dan bukan URL eksternal)
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }

            // 2. Simpan gambar baru
            // Akan disimpan di storage/app/public/avatars
            $validated['avatar'] = $this->image->store('avatars', 'public');
        }

        // Hapus 'image' dari array validated karena kolom di DB namanya 'avatar'
        unset($validated['image']);

        $user->update($validated);

        // Update oldImage agar preview berubah tanpa refresh
        if (isset($validated['avatar'])) {
            $this->oldImage = $validated['avatar'];
            $this->reset('image'); // Reset input file
        }

        $this->dispatch('notify', [
            'message' => 'Profil berhasil diperbarui!',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.user-profile.edit-profile');
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk update profil pelanggan.
 * Mendukung upload avatar (file gambar).
 * Digunakan oleh AuthController@updateProfile.
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'birth_date'  => 'nullable|date|before:today',
            'gender'      => 'nullable|in:male,female',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Maks 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'avatar.image'   => 'File harus berupa gambar.',
            'avatar.mimes'   => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'avatar.max'     => 'Ukuran gambar maksimal 2MB.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'gender.in'      => 'Gender harus male atau female.',
        ];
    }
}

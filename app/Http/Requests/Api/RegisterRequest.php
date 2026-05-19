<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk registrasi pelanggan baru.
 * Digunakan oleh AuthController@register.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Endpoint publik, siapapun bisa register
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone'    => 'nullable|string|max:20',
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ];
    }
}

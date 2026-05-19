<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk ganti password.
 * Memerlukan password lama sebagai verifikasi keamanan.
 */
class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed', // Flutter harus kirim 'new_password_confirmation'
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'  => 'Password lama wajib diisi.',
            'new_password.required'      => 'Password baru wajib diisi.',
            'new_password.min'           => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'     => 'Konfirmasi password baru tidak cocok.',
        ];
    }
}

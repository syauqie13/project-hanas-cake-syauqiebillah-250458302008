<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk menambah alamat pelanggan baru.
 */
class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:100',      // Label: Rumah, Kantor, dll.
            'detail_address' => 'required|string|max:500',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'is_primary'     => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'Label alamat wajib diisi (contoh: Rumah, Kantor).',
            'detail_address.required' => 'Detail alamat wajib diisi.',
            'receiver_name.required'  => 'Nama penerima wajib diisi.',
            'receiver_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'latitude.between'        => 'Latitude harus antara -90 dan 90.',
            'longitude.between'       => 'Longitude harus antara -180 dan 180.',
        ];
    }
}

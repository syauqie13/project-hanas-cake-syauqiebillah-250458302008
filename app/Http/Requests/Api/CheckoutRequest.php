<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk proses checkout.
 *
 * Aturan:
 * - delivery_type: hanya 'pickup' atau 'delivery'
 * - store_id: WAJIB untuk semua tipe (pelanggan harus pilih toko)
 * - address_id: WAJIB jika delivery_type = 'delivery'
 * - Tidak ada shipping zone (ongkir dihitung mekanisme lain)
 * - Tidak ada voucher (fitur dihapus)
 */
class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_type'      => 'required|in:pickup,delivery',
            'store_id'           => 'required|exists:stores,id',
            'address_id'         => 'required_if:delivery_type,delivery|nullable|exists:customer_addresses,id',
            'total_belanja'      => 'required|numeric|min:1',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'notes'              => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_type.required'    => 'Tipe pengiriman wajib dipilih.',
            'delivery_type.in'          => 'Tipe pengiriman harus pickup atau delivery.',
            'store_id.required'         => 'Toko wajib dipilih.',
            'store_id.exists'           => 'Toko tidak ditemukan.',
            'address_id.required_if'    => 'Alamat wajib dipilih untuk pengiriman delivery.',
            'address_id.exists'         => 'Alamat tidak ditemukan.',
            'total_belanja.required'    => 'Total belanja wajib diisi.',
            'total_belanja.min'         => 'Total belanja minimal Rp 1.',
            'items.required'            => 'Keranjang belanja tidak boleh kosong.',
            'items.min'                 => 'Minimal 1 item dalam keranjang.',
            'items.*.product_id.exists' => 'Salah satu produk tidak ditemukan.',
            'items.*.quantity.min'      => 'Jumlah item minimal 1.',
        ];
    }
}

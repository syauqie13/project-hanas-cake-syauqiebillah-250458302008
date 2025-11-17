<?php

namespace App\Imports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class InventoryImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Normalisasi input (huruf kecil semua biar aman)
        $type = strtolower($row['tipe']);
        $unit = strtolower($row['satuan']);

        return Inventory::updateOrCreate(
            ['name' => $row['nama_barang']], // Cari berdasarkan Nama Barang
            [
                'type' => $type,
                'unit' => $unit,
                'stock' => $row['stok_saat_ini'],
                'unit_price' => $row['harga_per_unit'],
                'description' => $row['deskripsi'] ?? null,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string',

            // Validasi Enum Tipe
            'tipe' => ['required', Rule::in(['bahan_baku', 'produk_jadi'])],

            // Validasi Enum Satuan
            'satuan' => ['required', Rule::in(['gram', 'ml', 'pcs', 'pack', 'box'])],

            'stok_saat_ini' => 'required|numeric|min:0',
            'harga_per_unit' => 'required|numeric|min:0',
        ];
    }

    // Custom Pesan Error agar Karyawan paham salahnya dimana
    public function customValidationMessages()
    {
        return [
            'tipe.in' => 'Kolom Tipe harus diisi: bahan_baku atau produk_jadi.',
            'satuan.in' => 'Kolom Satuan harus diisi: gram, ml, pcs, pack, atau box.',
        ];
    }
}

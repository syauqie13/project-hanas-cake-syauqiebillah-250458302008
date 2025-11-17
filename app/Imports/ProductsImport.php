<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Proses tiap baris Excel
     */
    public function model(array $row)
    {
        // --- STEP 1: Handle Kategori ---
        $namaKategori = trim($row['kategori'] ?? 'Umum'); // default 'Umum' jika kosong
        $category = Category::firstOrCreate(
            ['name' => $namaKategori],
            [
                'name' => $namaKategori,
                'slug' => Str::slug($namaKategori),
            ]
        );

        // --- STEP 2: Handle Gambar ---
        $imagePath = null;
        if (!empty($row['nama_file_gambar'])) {
            $imageFile = trim($row['nama_file_gambar']);
            $imagePath = 'products/' . $imageFile; // Pastikan folder storage/products sudah ada
        }

        // --- STEP 3: Handle Produk ---
        // Update jika nama produk sudah ada, atau buat baru
        $product = Product::updateOrCreate(
            ['name' => $row['nama_kue']], // kunci update
            [
                'category_id' => $category->id,
                'price' => $row['harga'] ?? 0, // default 0 jika kosong
                'stock' => $row['stok'] ?? 0,  // default 0 jika kosong
                'discount' => $row['diskon'] ?? 0, // default 0
                'image' => $imagePath,
                'slug' => Str::slug($row['nama_kue']), // otomatis buat slug
            ]
        );

        return $product;
    }

    /**
     * Rules validasi untuk tiap baris
     */
    public function rules(): array
    {
        return [
            'nama_kue' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'nama_file_gambar' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom message validasi (opsional)
     */
    public function customValidationMessages()
    {
        return [
            'nama_kue.required' => 'Kolom Nama Kue wajib diisi.',
            'kategori.required' => 'Kolom Kategori wajib diisi.',
            'harga.required' => 'Kolom Harga wajib diisi.',
            'stok.required' => 'Kolom Stok wajib diisi.',
            'diskon.numeric' => 'Diskon harus berupa angka 0-100.',
        ];
    }
}

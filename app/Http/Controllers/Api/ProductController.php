<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Ambil Semua Kategori
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Kategori',
            'data' => $categories
        ]);
    }

    /**
     * Ambil Daftar Produk (dengan fitur Filter & Search)
     */
    public function index(Request $request)
    {
        // Query dasar dengan memuat relasi kategori
        $query = Product::with('category');

        // Filter berdasarkan Kategori jika ada parameter ?category_id=...
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Fitur Pencarian jika ada parameter ?search=...
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Gunakan paginate (misal 10 data per halaman) agar Flutter bisa scroll tanpa berat
        $products = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Produk',
            'data' => $products
        ]);
    }

    /**
     * Detail Produk Spesifik
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Produk',
            'data' => $product
        ]);
    }
}
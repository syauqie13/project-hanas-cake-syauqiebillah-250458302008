<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

/**
 * StoreController
 *
 * Endpoint publik untuk menampilkan daftar toko yang aktif.
 * Digunakan oleh Flutter saat pelanggan memilih toko untuk
 * pickup, delivery, maupun preorder.
 */
class StoreController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/stores
     *
     * Mengembalikan daftar semua toko yang aktif (is_active = true).
     * Data mencakup nama, alamat, koordinat, dan jam operasional.
     */
    public function index(): JsonResponse
    {
        $stores = Store::where('is_active', true)
            ->orderBy('name')
            ->get();

        return $this->successResponse($stores, 'Daftar toko berhasil diambil');
    }
}

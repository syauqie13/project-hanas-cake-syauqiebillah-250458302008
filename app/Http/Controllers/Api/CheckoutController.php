<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Services\CheckoutService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

/**
 * CheckoutController
 *
 * Menangani proses checkout dari aplikasi mobile (Flutter).
 *
 * Perubahan dari versi sebelumnya:
 * - Voucher dihapus (fitur tidak digunakan lagi)
 * - Shipping zone dihapus (mekanisme ongkir sudah diganti)
 * - Ditambahkan store_id (wajib untuk semua tipe: pickup & delivery)
 * - Ditambahkan address_id (wajib untuk delivery)
 * - Business logic dipindahkan ke CheckoutService
 */
class CheckoutController extends Controller
{
    use ApiResponseTrait;

    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * POST /api/checkout
     *
     * Memproses checkout: buat order, simpan items, dan generate Snap Token Midtrans.
     *
     * Request Body:
     * {
     *   "delivery_type": "pickup|delivery",
     *   "store_id": 1,
     *   "address_id": 2,              // Wajib jika delivery
     *   "total_belanja": 50000,
     *   "items": [
     *     { "product_id": 1, "quantity": 2, "price": 25000 }
     *   ],
     *   "notes": "Topping extra coklat" // Opsional
     * }
     */
    public function process(CheckoutRequest $request): JsonResponse
    {
        try {
            $result = $this->checkoutService->processCheckout(
                $request->user(),
                $request->validated()
            );

            return $this->successResponse([
                'order_id'   => $result['order']->id,
                'snap_token' => $result['snap_token'],
            ], 'Checkout Berhasil');
        } catch (\Exception $e) {
            return $this->errorResponse('Checkout gagal: ' . $e->getMessage(), 500);
        }
    }
}
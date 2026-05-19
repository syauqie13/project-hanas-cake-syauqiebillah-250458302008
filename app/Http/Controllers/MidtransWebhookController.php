<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderService;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MidtransWebhookController
 *
 * Menerima dan memproses notifikasi callback (webhook) dari Midtrans.
 *
 * ALUR KERJA:
 * 1. Midtrans mengirim POST request ke /api/midtrans/webhook
 * 2. Controller memvalidasi signature SHA-512 (anti-spoofing)
 * 3. Berdasarkan transaction_status:
 *    - settlement/capture → order berhasil dibayar
 *    - cancel/deny/expire → order gagal
 * 4. Stok inventaris dikurangi otomatis setelah pembayaran sukses
 * 5. Notifikasi dikirim ke pelanggan
 *
 * KEAMANAN:
 * - Route ini di-exclude dari CSRF verification (lihat bootstrap/app.php)
 * - Signature diverifikasi via MidtransService
 * - Proteksi double-request: jika order sudah 'paid', webhook diabaikan
 */
class MidtransWebhookController extends Controller
{
    protected MidtransService $midtransService;
    protected OrderService $orderService;

    public function __construct(MidtransService $midtransService, OrderService $orderService)
    {
        $this->midtransService = $midtransService;
        $this->orderService    = $orderService;
    }

    public function handle(Request $request)
    {
        // Bypass ngrok browser warning
        header('ngrok-skip-browser-warning: 1');

        // Parse payload — support baik dari JSON body maupun form-data
        $payload = $request->all();
        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true);
        }

        if (!$payload) {
            return response()->json(['message' => 'Empty Payload'], 400);
        }

        $orderId           = $payload['order_id'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = number_format($payload['gross_amount'], 2, '.', '');
        $signatureKey      = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        // KEAMANAN: Validasi signature dari Midtrans
        if (!$this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::error("Webhook: Signature mismatch untuk order {$orderId}");
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // Cari order berdasarkan merchant_order_id
        $order = Order::where('merchant_order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order Not Found'], 404);
        }

        // PROTEKSI DOUBLE REQUEST: Jika sudah paid, langsung return 200
        if ($order->payment_status === 'paid' && in_array($transactionStatus, ['settlement', 'capture'])) {
            Log::info("Webhook diabaikan: Order {$orderId} sudah berstatus Paid.");
            return response()->json(['message' => 'Already Paid'], 200);
        }

        DB::beginTransaction();
        try {
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $this->orderService->handlePaymentSuccess($order, $payload['gross_amount']);

                // Kirim notifikasi ke pelanggan: "Pembayaran berhasil"
                if ($order->user) {
                    $order->user->notify(new OrderStatusNotification($order, 'diproses'));
                }
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $this->orderService->handlePaymentFailed($order);

                // Kirim notifikasi ke pelanggan: "Pesanan dibatalkan"
                if ($order->user) {
                    $order->user->notify(new OrderStatusNotification($order, 'dibatalkan'));
                }
            }

            DB::commit();
            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook Error: " . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}
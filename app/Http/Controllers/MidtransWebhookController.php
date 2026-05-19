<?php

namespace App\Http\Controllers; // Pastikan namespace ini sesuai dengan lokasi file Anda

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductRecipe;
use App\Models\Inventory;
use App\Models\UserVoucher;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        header('ngrok-skip-browser-warning: 1');

        $payload = $request->all();
        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true);
        }

        if (!$payload) {
            return response()->json(['message' => 'Empty Payload'], 400);
        }

        Config::$serverKey = config('services.midtrans.server_key');

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = number_format($payload['gross_amount'], 2, '.', ''); 
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        // Validasi Signature
        $input = $orderId . $statusCode . $grossAmount . Config::$serverKey;
        $signature = hash("sha512", $input);

        if ($signature !== $signatureKey) {
            Log::error("Signature Mismatch! Order: $orderId. Generated: $signature, From Midtrans: $signatureKey");
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $order = Order::where('merchant_order_id', $orderId)->first();

        if ($order) {
            // MENCEGAH BUG DOUBLE REQUEST: Jika order sudah lunas, langsung kembalikan 200 OK tanpa eksekusi ulang
            if ($order->payment_status == 'paid' && ($transactionStatus == 'settlement' || $transactionStatus == 'capture')) {
                Log::info("Webhook diabaikan: Order $orderId sudah berstatus Paid sebelumnya.");
                return response()->json(['message' => 'Already Paid'], 200);
            }

            DB::beginTransaction();
            try {
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    $this->handleSuccess($order, $payload['gross_amount']);
                } 
                // TAMBAHAN: Menangani jika user batal bayar atau waktu expired
                elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $this->handleFailed($order);
                }

                DB::commit();
                return response()->json(['message' => 'OK'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Webhook Error: " . $e->getMessage());
                return response()->json(['message' => 'Error processing webhook'], 500);
            }
        }

        return response()->json(['message' => 'Order Not Found'], 404);
    }

    private function handleSuccess($order, $grossAmount)
    {
        $order->payment_status = 'paid';
        $order->paid_amount = $grossAmount;

        if ($order->order_type == 'online') {
            $order->status = 'diproses'; // Disesuaikan dengan bahasa Indonesia jika ada
        } else {
            $order->status = 'selesai';
        }
        $order->save();

        // Update Voucher
        if ($order->voucher_id) {
            UserVoucher::where('user_id', $order->user_id)
                ->where('voucher_id', $order->voucher_id)
                ->update(['is_used' => true, 'used_at' => now()]);
        }

        $this->reduceInventoryStock($order);
        Log::info("Order $order->merchant_order_id berhasil dilunasi via Webhook.");
    }

    // FUNGSI BARU: Untuk mengubah status gagal secara otomatis
    private function handleFailed($order)
    {
        $order->payment_status = 'failed';
        $order->status = 'dibatalkan';
        $order->save();

        // Kembalikan voucher agar bisa dipakai lagi oleh user
        if ($order->voucher_id) {
            UserVoucher::where('user_id', $order->user_id)
                ->where('voucher_id', $order->voucher_id)
                ->update(['is_used' => false, 'used_at' => null]);
        }
        
        Log::info("Order $order->merchant_order_id dibatalkan/expired via Webhook.");
    }

    private function reduceInventoryStock($order)
    {
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $item) {
            $recipes = ProductRecipe::where('product_id', $item->product_id)->get();
            foreach ($recipes as $recipe) {
                Inventory::where('id', $recipe->inventory_id)
                    ->decrement('stock', $recipe->quantity_used * $item->jumlah);
            }
        }
    }
}
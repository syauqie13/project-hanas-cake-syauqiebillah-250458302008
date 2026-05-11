<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
    
    // PAKSA format gross_amount agar sama dengan kiriman Midtrans (2 desimal)
    // Midtrans mengirim "35000.00", maka kita harus pakai format yang sama untuk Signature
    $grossAmount = number_format($payload['gross_amount'], 2, '.', ''); 
    
    $signatureKey = $payload['signature_key'] ?? null;
    $transactionStatus = $payload['transaction_status'] ?? null;

    // Validasi Signature
    $input = $orderId . $statusCode . $grossAmount . Config::$serverKey;
    $signature = hash("sha512", $input);

    if ($signature !== $signatureKey) {
        Log::error("Signature Mismatch! Order: $orderId. Generated: $signature, From Midtrans: $signatureKey");
        // Jika masih gagal 400, coba matikan return 403 ini sementara untuk tes apakah bisa tembus 200
        return response()->json(['message' => 'Invalid Signature'], 403);
    }

    $order = Order::where('merchant_order_id', $orderId)->first();

    if ($order) {
        DB::beginTransaction();
        try {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $this->handleSuccess($order, $payload['gross_amount']);
            }
            DB::commit();
            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook Error: " . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    return response()->json(['message' => 'Not Found'], 404);
}

    private function handleSuccess($order, $grossAmount)
    {
        $order->payment_status = 'paid';
        $order->paid_amount = $grossAmount;

        if ($order->order_type == 'online') {
            $order->status = 'processing';
        } else {
            $order->status = 'completed';
        }
        $order->save();

        // Update Voucher
        if ($order->voucher_id) {
            UserVoucher::where('user_id', $order->user_id)
                ->where('voucher_id', $order->voucher_id)
                ->update(['is_used' => true, 'used_at' => now()]);
        }

        $this->reduceInventoryStock($order);
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
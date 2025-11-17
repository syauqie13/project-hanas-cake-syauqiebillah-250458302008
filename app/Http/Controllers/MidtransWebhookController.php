<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Inventory;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            // 2. Buat instance Notification untuk membaca payload
            $notif = new Notification();

            $orderId = $notif->order_id;
            $statusCode = $notif->status_code;
            $grossAmount = $notif->gross_amount;
            $transactionStatus = $notif->transaction_status;
            $type = $notif->payment_type;
            $fraud = $notif->fraud_status;

            // 3. Validasi Keamanan (Signature Key)
            $input = $orderId . $statusCode . $grossAmount . Config::$serverKey;
            $signature = openssl_digest($input, 'sha512');

            if ($signature != $notif->signature_key) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // 4. Temukan Order di Database
            $order = Order::where('merchant_order_id', $orderId)->first();

            if (!$order) {
                Log::warning("Webhook: Order not found for merchant_order_id: $orderId");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 5. Proses Status Transaksi
            DB::beginTransaction();

            if ($order->payment_status === 'pending') {

                if ($transactionStatus == 'capture') {
                    if ($fraud == 'accept') {
                        $this->handleSuccess($order, $grossAmount);
                    }
                } else if ($transactionStatus == 'settlement') {
                    $this->handleSuccess($order, $grossAmount);
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    $order->save();
                } else if ($transactionStatus == 'pending') {
                    $order->payment_status = 'pending';
                    $order->save();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Notification processed'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Webhook Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menangani logika saat pembayaran BERHASIL
     */
    private function handleSuccess($order, $grossAmount)
    {
        // 1. Update Status Pembayaran (Sama untuk semua)
        $order->payment_status = 'paid';
        $order->paid_amount = $grossAmount;

        // ================================================
        // === INI ADALAH PERBAIKAN LOGIKA STATUS ANDA ===
        // ================================================

        // Cek tipe ordernya
        if ($order->order_type == 'online') {
            // Jika ini order PO E-commerce, set ke 'processing'
            $order->status = 'processing';
            Log::info("Webhook: Order PO (ID: {$order->id}) LUNAS. Status diubah ke 'processing'.");
        } else {
            // Jika ini order POS (atau tipe lain), set ke 'completed'
            $order->status = 'completed';
            Log::info("Webhook: Order POS (ID: {$order->id}) LUNAS. Status diubah ke 'completed'.");
        }
        // ================================================
        // === AKHIR PERBAIKAN ===
        // ================================================

        $order->save();

        // 2. Kurangi Stok Bahan Baku (Inventories)
        // (Logika ini tetap berjalan untuk kedua tipe order)
        $this->reduceInventoryStock($order);
    }

    /**
     * Menangani pengurangan stok bahan baku berdasarkan resep
     */
    private function reduceInventoryStock($order)
    {
        try {
            $orderItems = OrderItem::where('order_id', $order->id)->get();

            foreach ($orderItems as $item) {
                // (Logika pengurangan stok bahan baku Anda tetap sama...)
                $recipes = ProductRecipe::where('product_id', $item->product_id)->get();
                if ($recipes->isEmpty()) {
                    Log::warning("Webhook: Tidak ada resep untuk Product ID: {$item->product_id}");
                    continue;
                }
                foreach ($recipes as $recipe) {
                    $inventoryItem = Inventory::where('id', $recipe->inventory_id)
                        ->lockForUpdate()
                        ->first();
                    if ($inventoryItem) {
                        $quantityToReduce = $recipe->quantity_used * $item->jumlah;
                        $inventoryItem->decrement('stock', $quantityToReduce);
                        Log::info("Webhook: Mengurangi stok {$inventoryItem->name} sebanyak {$quantityToReduce}.");
                    } else {
                        Log::error("Webhook: Bahan baku ID {$recipe->inventory_id} tidak ditemukan.");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Webhook: Gagal mengurangi stok untuk Order ID {$order->id}: " . $e->getMessage());
        }
    }
}

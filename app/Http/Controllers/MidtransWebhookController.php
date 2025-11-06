<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;       // 1. Tambahkan Model ini
use App\Models\ProductRecipe;  // 2. Tambahkan Model ini
use App\Models\Inventory;      // 3. Tambahkan Model ini
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;  // 4. Tambahkan DB
use Illuminate\Support\Facades\Log; // 5. Tambahkan Log (untuk debug)

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            // 2. Buat instance Notification
            $notif = new Notification();

            // 3. Terima payload
            $payload = $request->all();
            $orderId = $payload['order_id'];
            $statusCode = $payload['status_code'];
            $grossAmount = $payload['gross_amount'];
            $transactionStatus = $payload['transaction_status'];
            $signatureKey = $payload['signature_key'];

            // 4. Validasi Keamanan
            $expectedSignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . Config::$serverKey);

            if ($signatureKey !== $expectedSignatureKey) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // 5. Temukan Order di Database Anda
            $order = Order::where('merchant_order_id', $orderId)->first();

            if (!$order) {
                Log::warning("Webhook: Order not found for merchant_order_id: $orderId");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 6. Update Status Order (Gunakan Transaksi Database)
            DB::beginTransaction();

            // Hanya proses jika statusnya masih 'pending'
            if ($order->payment_status === 'pending') {
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {

                    // --- TAHAP 1: UPDATE STATUS ORDER ---
                    $order->payment_status = 'paid';
                    $order->status = 'completed'; // Atau 'processing' jika perlu
                    $order->paid_amount = $grossAmount;
                    $order->save();

                    DB::commit(); // Commit perubahan status order

                    // --- TAHAP 2: KURANGI STOK INVENTARIS ---
                    // Kita lakukan ini SETELAH commit status, agar jika
                    // pengurangan stok gagal, status order tetap 'paid'.
                    try {
                        Log::info("Webhook: Memulai pengurangan stok untuk Order ID: $order->id");
                        $orderItems = OrderItem::where('order_id', $order->id)->get();

                        foreach ($orderItems as $item) {
                            Log::info("Webhook: Mencari resep untuk Product ID: " . $item->product_id);
                            $recipes = ProductRecipe::where('product_id', $item->product_id)->get();

                            if ($recipes->isEmpty()) {
                                Log::warning("Webhook: TIDAK DITEMUKAN resep untuk Product ID: " . $item->product_id);
                            } else {
                                Log::info("Webhook: Ditemukan " . $recipes->count() . " resep.");
                            }

                            foreach ($recipes as $recipe) {
                                Log::info("Webhook: Memproses resep: Butuh Inventory ID: " . $recipe->inventory_id . " sebanyak " . $recipe->quantity_used);
                                $inventoryItem = Inventory::find($recipe->inventory_id);
                                if ($inventoryItem) {
                                    $quantityToReduce = $recipe->quantity_used * $item->jumlah; // Gunakan $item->jumlah
                                    Log::info("Webhook: -> Stok " . $inventoryItem->name . " saat ini: " . $inventoryItem->stock . ". Akan dikurangi: " . $quantityToReduce);
                                    $inventoryItem->decrement('stock', $quantityToReduce);
                                    Log::info("Webhook: -> Stok " . $inventoryItem->name . " berhasil dikurangi.");
                                } else {
                                    Log::error("Webhook: -> GAGAL! Item inventaris dengan ID: " . $recipe->inventory_id . " TIDAK DITEMUKAN.");
                                }
                            }
                        }
                    } catch (\Exception $stockError) {
                        // Jika pengurangan stok gagal, JANGAN batalkan transaksi
                        // Cukup catat errornya, karena uang sudah diterima.
                        Log::error("Webhook: PENGURANGAN STOK GAGAL untuk Order ID: $order->id. Error: " . $stockError->getMessage());
                    }

                } else if ($transactionStatus == 'expire') {
                    $order->payment_status = 'expired';
                    $order->status = 'cancelled';
                    $order->save();
                    DB::commit();
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    $order->save();
                    DB::commit();
                } else {
                    // Status lain (misal: pending) tidak perlu diapa-apakan
                    DB::rollBack();
                }
            } else {
                // Order sudah diproses (bukan 'pending' lagi), tidak perlu rollback
                DB::rollBack();
                Log::info("Webhook: Order $orderId sudah diproses sebelumnya (status: " . $order->payment_status . "). Tidak ada update.");
            }

            // 7. Kirim respon OK ke Midtrans
            return response()->json(['message' => 'Notification processed'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Error (Utama): ' . $e->getMessage());
            return response()->json(['message' => 'Webhook Error: ' . $e->getMessage()], 500);
        }
    }
}

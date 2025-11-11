<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;         // 1. Tambahkan Model Product
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
                    $order->status = 'completed';
                    $order->paid_amount = $grossAmount;
                    $order->save();

                    DB::commit(); // Commit perubahan status order (UANG SUDAH MASUK)

                    // --- TAHAP 2: KURANGI STOK (OPERASIONAL) ---
                    // Logika ini disamakan dengan 'prosesPembayaranTunai'
                    DB::beginTransaction(); // <-- Transaksi BARU terpisah untuk stok
                    try {
                        Log::info("Webhook: Memulai pengurangan stok untuk Order ID: $order->id");
                        $orderItems = OrderItem::where('order_id', $order->id)->get();

                        foreach ($orderItems as $item) {

                            // 4️⃣ Kurangi stok produk utama (dari kode baru Anda)
                            $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                            if ($product) {
                                if ($product->stock < $item->jumlah) {
                                    // BEDA DENGAN TUNAI: Jangan 'throw', tapi 'Log::error'
                                    // Kita tidak bisa membatalkan pembayaran yang sudah lunas.
                                    Log::error("Webhook: Stok produk {$product->name} (Order ID: {$order->id}) TIDAK MENCUKUPI. Transaksi tetap lunas.");
                                } else {
                                    $product->decrement('stock', $item->jumlah);
                                    Log::info("Webhook: Stok produk {$product->name} dikurangi {$item->jumlah}");
                                }
                            }

                            // 5️⃣ Kurangi stok bahan baku (dari kode baru Anda)
                            $recipes = ProductRecipe::where('product_id', $item->product_id)->get();
                            foreach ($recipes as $recipe) {
                                $inventoryItem = Inventory::where('id', $recipe->inventory_id)->lockForUpdate()->first();
                                if ($inventoryItem) {
                                    $quantityToReduce = $recipe->quantity_used * $item->jumlah;
                                    if ($inventoryItem->stock < $quantityToReduce) {
                                        // BEDA DENGAN TUNAI: Jangan 'throw', tapi 'Log::error'
                                        Log::error("Webhook: Stok bahan {$inventoryItem->name} (Order ID: {$order->id}) TIDAK MENCUKUPI. Transaksi tetap lunas.");
                                    } else {
                                        $inventoryItem->decrement('stock', $quantityToReduce);
                                        Log::info("Webhook: Stok bahan {$inventoryItem->name} dikurangi {$quantityToReduce}");
                                    }
                                } else {
                                    Log::warning("Webhook: Item inventaris dengan ID: {$recipe->inventory_id} tidak ditemukan untuk resep produk ID: {$item->product_id}.");
                                }
                            }
                        }

                        DB::commit(); // Commit pengurangan stok
                        Log::info("Webhook: Pengurangan stok untuk Order ID: $order->id SELESAI.");

                    } catch (\Exception $stockError) {
                        DB::rollBack(); // Batalkan HANYA pengurangan stok
                        Log::error("Webhook: PENGURANGAN STOK GAGAL (DB Rollback) untuk Order ID: $order->id. Error: " . $stockError->getMessage());
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
                    DB::rollBack(); // (status pending, dll)
                }
            } else {
                DB::rollBack(); // (sudah diproses)
                Log::info("Webhook: Order $orderId sudah diproses sebelumnya (status: " . $order->payment_status . "). Tidak ada update.");
            }

            // 7. Kirim respon OK ke Midtrans
            return response()->json(['message' => 'Notification processed'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // (Rollback utama jika ada)
            Log::error('Webhook Error (Utama): ' . $e->getMessage());
            return response()->json(['message' => 'Webhook Error: ' . $e->getMessage()], 500);
        }
    }
}

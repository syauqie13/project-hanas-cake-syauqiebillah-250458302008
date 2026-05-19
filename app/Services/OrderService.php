<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductRecipe;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * OrderService
 *
 * Menangani business logic terkait order setelah checkout:
 * - Update status pembayaran (dari webhook Midtrans)
 * - Pengurangan stok inventaris berdasarkan resep produk
 * - Penanganan order gagal/expired
 */
class OrderService
{
    /**
     * Proses order yang berhasil dibayar.
     *
     * Langkah-langkah:
     * 1. Update status pembayaran → 'paid'
     * 2. Update status order → 'diproses' (online) / 'selesai' (offline/POS)
     * 3. Kurangi stok inventaris sesuai resep produk
     *
     * @param Order  $order       Order yang statusnya akan diupdate
     * @param string $grossAmount Nominal yang dibayar
     */
    public function handlePaymentSuccess(Order $order, string $grossAmount): void
    {
        $order->payment_status = 'paid';
        $order->paid_amount    = $grossAmount;

        // Order online → 'diproses' (menunggu dikirim/diambil)
        // Order POS → langsung 'selesai'
        $order->status = ($order->order_type === 'online') ? 'diproses' : 'selesai';
        $order->save();

        // Kurangi stok bahan baku berdasarkan resep
        $this->reduceInventoryStock($order);

        Log::info("Order {$order->merchant_order_id} berhasil dilunasi.");
    }

    /**
     * Proses order yang gagal/dibatalkan/expired.
     *
     * @param Order $order Order yang gagal
     */
    public function handlePaymentFailed(Order $order): void
    {
        $order->payment_status = 'failed';
        $order->status         = 'dibatalkan';
        $order->save();

        Log::info("Order {$order->merchant_order_id} dibatalkan/expired.");
    }

    /**
     * Mengurangi stok inventaris berdasarkan resep setiap produk yang dipesan.
     *
     * Alur:
     * 1. Ambil semua item dari order
     * 2. Untuk setiap item, cari resepnya (ProductRecipe)
     * 3. Kurangi stok inventaris = quantity_used × jumlah item yang dipesan
     *
     * @param Order $order
     */
    private function reduceInventoryStock(Order $order): void
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

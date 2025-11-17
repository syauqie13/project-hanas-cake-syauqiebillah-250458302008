<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerPaymentController extends Controller
{
    public function show($id)
    {
        // 1. Cari Order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'pending')
            ->with('items.product', 'user')
            ->firstOrFail();

        // 2. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$is3ds = false;
        Config::$isSanitized = true;

        // 3. Regenerasi Merchant Order ID
        $newMerchantOrderId = 'PO-' . $order->id . '-' . time();
        $order->merchant_order_id = $newMerchantOrderId;
        $order->save();

        // 4. Item Details
        $item_details = [];
        foreach ($order->items as $item) {
            $item_details[] = [
                'id' => $item->product_id,
                'price' => (int) $item->harga_satuan,
                'quantity' => (int) $item->jumlah,
                'name' => substr($item->product->name ?? 'Produk', 0, 50)
            ];
        }

        // 5. Parameter Transaksi (+ SETTING EXPIRY)
        $params = [
            'transaction_details' => [
                'order_id' => $newMerchantOrderId,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name ?? $order->user->name,
                'email' => $order->shipping_email ?? $order->user->email,
                'phone' => $order->shipping_phone ?? $order->user->phone,
            ],
            'item_details' => $item_details,

            // ============================================
            // === FITUR BARU: EXPIRE DALAM 1 JAM (60 Menit) ===
            // ============================================
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"), // Waktu mulai = Sekarang
                'unit' => 'minutes',
                'duration' => 60 // Durasi = 60 Menit
            ],
        ];

        // 6. Dapatkan Token Baru
        $snapToken = Snap::getSnapToken($params);

        return view('frontend.payment', [
            'order' => $order,
            'snapToken' => $snapToken
        ]);
    }
}

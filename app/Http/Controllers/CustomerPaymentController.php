<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerPaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran atau memproses Snap Token
     */
    public function show(Request $request, $id)
    {
        // 1. Cari Order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'pending')
            ->with(['items.product', 'user'])
            ->firstOrFail();

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$is3ds = false;
        Config::$isSanitized = true;

        // 4. Regenerasi Merchant Order ID (Agar tidak duplicate di Midtrans)
        $newMerchantOrderId = 'PO-' . $order->id . '-' . time();
        $order->merchant_order_id = $newMerchantOrderId;
        $order->save();

        // 5. Item Details (Produk + Ongkir + Diskon)
        $item_details = [];
        foreach ($order->items as $item) {
            $item_details[] = [
                'id' => 'PROD-' . $item->product_id,
                'price' => (int) $item->harga_satuan,
                'quantity' => (int) $item->jumlah,
                'name' => substr($item->product->name ?? 'Produk', 0, 50)
            ];
        }

        if ($order->shipping_price > 0) {
            $item_details[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_price,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman'
            ];
        }

        // 6. Parameter Transaksi
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
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit' => 'minutes',
                'duration' => 60 
            ],
        ];

        // 7. Dapatkan Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
            
            return view('frontend.payment', [
                'order' => $order,
                'snapToken' => $snapToken
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }
    }
}
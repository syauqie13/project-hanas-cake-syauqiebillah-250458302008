<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone; // Pastikan Model ini sudah ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // 1. Validasi Payload (Tambahkan delivery_type dan shipping_zone_id kondisional)
        $request->validate([
            'delivery_type' => 'required|in:pickup,delivery',
            'shipping_zone_id' => 'required_if:delivery_type,delivery|exists:shipping_zones,id',
            'total_belanja' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        $user = $request->user();

        // 2. Ambil data Zona Pengiriman dari database jika tipe pengiriman adalah delivery
        $ongkir = 0;
        $zoneName = null;

        if ($request->delivery_type === 'delivery') {
            $zone = ShippingZone::find($request->shipping_zone_id);
            if ($zone) {
                $ongkir = $zone->price;
                $zoneName = $zone->name;
            }
        } else {
            $zoneName = 'Ambil di Toko (Pickup)';
        }

        $grandTotal = $request->total_belanja + $ongkir;

        DB::beginTransaction();

        try {
            $merchantOrderId = 'HANA-ONL-' . strtoupper(Str::random(6));

            // 3. Simpan ke tabel Orders sesuai skemamu
            $order = Order::create([
                'user_id' => $user->id,
                'cashier_id' => 1, // ID Admin/Sistem default
                'tanggal' => now(),
                'total' => $grandTotal,
                'merchant_order_id' => $merchantOrderId,
                'payment_status' => 'pending',
                'order_type' => 'online',
                'delivery_type' => $request->delivery_type,
                'status' => 'pending',

                // Data Alamat & Zona
                'shipping_name' => $user->name,
                'shipping_email' => $user->email,
                'shipping_phone' => $user->phone,
                'shipping_address' => $request->delivery_type === 'delivery' ? $user->address : null,
                'shipping_city' => $request->delivery_type === 'delivery' ? $user->city : null,
                'shipping_postal_code' => $request->delivery_type === 'delivery' ? $user->postal_code : null,

                // Ambil dari tabel shipping_zones atau hardcode jika pickup
                'shipping_zone_name' => $zoneName, 
                'shipping_price' => $ongkir,
            ]);

            // 4. Simpan OrderItems
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            // 5. Integrasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '080000000000',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout Berhasil',
                'data' => [
                    'order_id' => $order->id,
                    'snap_token' => $snapToken
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getShippingZones()
    {
        $zones = ShippingZone::all();
        return response()->json([
            'success' => true,
            'data' => $zones
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan user yang sedang login
     */
    public function index(Request $request)
    {
        // Ambil pesanan milik pelanggan ini saja, urutkan dari yang terbaru
        // Kita juga memuat relasi 'orderItems' agar Flutter tahu berapa jumlah barang di tiap pesanan
        $orders = Order::with('Items')
            ->where('user_id', $request->user()->id)
            ->where('order_type', 'online') // Pastikan hanya pesanan dari aplikasi
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($order) {
                return $this->formatOrder($order);
            });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Riwayat Pesanan',
            'data'    => $orders
        ]);
    }

    /**
     * Menampilkan detail satu pesanan secara spesifik
     */
    public function show(Request $request, $id)
    {
        // Cari pesanan berdasarkan ID, pastikan milik user yang sedang login
        // Muat relasi orderItems beserta detail produknya (nama produk, gambar)
        $order = Order::with('Items.product') 
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $order = $this->formatOrder($order);

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan',
            'data'    => $order
        ]);
    }

    /**
     * Helper untuk memformat order dengan atribut pickup & antrean dinamis (Flutter-ready)
     */
    private function formatOrder(Order $order)
    {
        // 1. Hitung nomor antrean 3 digit
        $queueNumber = sprintf('%03d', $order->id % 1000);
        if ($queueNumber === '000') {
            $queueNumber = '999';
        }
        $order->queue_number = $queueNumber;

        // 2. Ambil detail store
        $storeName = $order->shipping_city;
        $store = \App\Models\Store::where('name', $storeName)->first() 
            ?? \App\Models\Store::where('is_active', true)->first();
        
        $order->store_details = $store;

        // 3. Hitung jarak
        $distance = null;
        if ($store && auth()->check()) {
            $customer = auth()->user()->customer;
            if ($customer) {
                $addressModel = \App\Models\CustomerAddress::where('customer_id', $customer->id)->where('is_primary', true)->first()
                    ?? \App\Models\CustomerAddress::where('customer_id', $customer->id)->first();
                if ($addressModel && $store->latitude && $addressModel->latitude) {
                    $distance = $this->calculateDistance(
                        $addressModel->latitude, 
                        $addressModel->longitude, 
                        $store->latitude, 
                        $store->longitude
                    );
                }
            }
        }
        $order->distance = $distance;

        return $order;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) 
    {
        $earthRadius = 6371;
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLon = deg2rad((float)$lon2 - (float)$lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }
}
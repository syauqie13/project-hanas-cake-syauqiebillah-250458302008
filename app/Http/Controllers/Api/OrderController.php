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
            ->get();

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

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan',
            'data'    => $order
        ]);
    }
}
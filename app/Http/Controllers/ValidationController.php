<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidationController extends Controller
{
    /**
     * Tampilkan halaman validasi pembayaran.
     * Menerima $merchantOrderId dari rute.
     */
    public function show(Request $request, $merchantOrderId)
    {
        Log::info("Membuka halaman validasi untuk: $merchantOrderId");

        try {
            // 1. Cari order berdasarkan ID unik Midtrans
            $order = Order::where('merchant_order_id', $merchantOrderId)
                ->with('items.product', 'customer', 'cashier') // Muat semua relasi
                ->firstOrFail(); // Gagal jika tidak ditemukan

            // 2. Kirim data order ke view
            return view('karyawan.validasi', [
                'order' => $order,
                'status_query' => $request->query('status') // (Jika ada ?status=pending)
            ]);

        } catch (\Exception $e) {
            Log::error("Gagal membuka halaman validasi: " . $e->getMessage());
            // Jika order tidak ditemukan, kembalikan ke POS dengan error
            return redirect()->route('karyawan.pos')
                ->with('error', 'Order tidak ditemukan atau tidak valid.');
        }
    }
}

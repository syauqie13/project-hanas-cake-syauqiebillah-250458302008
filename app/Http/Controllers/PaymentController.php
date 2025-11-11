<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // (Opsional) Untuk mengambil detail order

class PaymentController extends Controller
{
    /**
     * Menangani redirect 'onSuccess' dari Snap
     * (Nama rute: karyawan.kasir.sukses)
     */
    public function sukses(Request $request)
    {
        // Kita bisa ambil orderId dari URL jika perlu
        $orderId = $request->query('order_id');
        // $order = Order::where('merchant_order_id', $orderId)->first();

        return view('karyawan.payment-status', [
            'title' => 'Pembayaran Berhasil!',
            'message' => "Transaksi untuk Order ID: $orderId telah berhasil. Stok akan segera diperbarui.",
            'icon' => 'success', // Untuk ikon (misal: Font Awesome 'fa-check-circle')
            'color' => 'success' // Untuk warna (misal: 'text-success')
        ]);
    }

    /**
     * Menangani redirect 'onPending' dari Snap
     * (Nama rute: karyawan.kasir.pending)
     */
    public function pending(Request $request)
    {
        $orderId = $request->query('order_id');

        return view('karyawan.payment-status', [
            'title' => 'Menunggu Pembayaran',
            'message' => "Transaksi untuk Order ID: $orderId sedang menunggu pembayaran (pending).",
            'icon' => 'info', // 'fa-hourglass-half'
            'color' => 'info'
        ]);
    }

    /**
     * Menangani redirect 'onError' dari Snap
     * (Nama rute: karyawan.kasir.error)
     */
    public function error(Request $request)
    {
        return view('karyawan.payment-status', [
            'title' => 'Pembayaran Gagal',
            'message' => "Terjadi kesalahan saat memproses pembayaran Anda. Silakan coba lagi.",
            'icon' => 'error', // 'fa-times-circle'
            'color' => 'danger'
        ]);
    }

    /**
     * Menangani redirect 'onClose' (batal) dari Snap
     * (Nama rute: karyawan.kasir.cancel)
     */
    public function cancel(Request $request)
    {
        return view('karyawan.payment-status', [
            'title' => 'Pembayaran Dibatalkan',
            'message' => "Anda telah membatalkan proses pembayaran.",
            'icon' => 'warning', // 'fa-exclamation-triangle'
            'color' => 'warning'
        ]);
    }
}

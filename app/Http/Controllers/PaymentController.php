<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function finish(Request $request)
    {
        // Ambil data dari query string (Midtrans kirim via parameter GET)
        $orderId = $request->query('order_id');
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        // Kamu bisa pakai data ini untuk menampilkan hasil pembayaran
        return view('payment.finish', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus,
        ]);
    }
}

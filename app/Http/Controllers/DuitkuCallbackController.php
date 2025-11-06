<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode'); // '00' = Sukses

        $merchantKey = config('services.duitku.merchant_key');

        // 1. Verifikasi Signature (SANGAT PENTING)
        $ownSignature = md5($merchantCode . $amount . $merchantOrderId . $merchantKey);

        if ($signature != $ownSignature) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // 2. Cari Order
        $realOrderId = explode('-', $merchantOrderId)[0];
        $order = Order::find($realOrderId);

        if ($order) {
            // 3. Cek Status Sukses ('00')
            if ($resultCode == '00') {
                // Update status order menjadi 'paid'
                $order->payment_status = 'paid';
                $order->save();
            } else {
                // Handle jika gagal atau pending
                $order->payment_status = 'failed';
                $order->save();
            }

            // Beri respons sukses ke DuitKu
            return response()->json(['message' => 'Callback received'], 200);
        }

        return response()->json(['message' => 'Order not found'], 404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
// use Illuminate_Preserve_Whitespace; // (Baris ini mungkin tidak diperlukan, tapi jaga-jaga)

class StrukController extends Controller
{
    /**
     * Tampilkan halaman struk untuk dicetak.
     */
    public function print(Order $order)
    {
        // Pastikan kita memuat relasi yang diperlukan (items dan produk di dalamnya)
        $order->load('items.product', 'customer', 'cashier');

        // Kirim data order ke view struk
        return view('karyawan.struk', [
            'order' => $order,
        ]);
    }
}

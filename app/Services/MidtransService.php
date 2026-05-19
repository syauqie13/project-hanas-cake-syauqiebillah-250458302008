<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

/**
 * MidtransService
 *
 * Mengenkapsulasi seluruh interaksi dengan Midtrans Payment Gateway.
 * Menghindari konfigurasi Midtrans tersebar di banyak controller.
 *
 * PENTING: Selalu gunakan config() bukan env() untuk membaca kredensial.
 * Penggunaan env() di luar file config akan mengembalikan null
 * jika konfigurasi di-cache (php artisan config:cache).
 */
class MidtransService
{
    public function __construct()
    {
        // Konfigurasi Midtrans dari config/services.php
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Generate Snap Token untuk pembayaran
     *
     * @param string $merchantOrderId ID unik order untuk Midtrans
     * @param int    $grossAmount     Total yang harus dibayar
     * @param array  $customerDetails Data pelanggan [first_name, email, phone]
     * @param array  $itemDetails     Detail item (opsional)
     * @return string Snap token untuk Midtrans payment popup
     *
     * @throws \Exception Jika gagal mendapatkan snap token dari Midtrans
     */
    public function createSnapToken(
        string $merchantOrderId,
        int $grossAmount,
        array $customerDetails,
        array $itemDetails = []
    ): string {
        $params = [
            'transaction_details' => [
                'order_id'     => $merchantOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit'       => 'minutes',
                'duration'   => 60, // Batas waktu pembayaran: 60 menit
            ],
        ];

        // Sertakan item details jika ada
        if (!empty($itemDetails)) {
            $params['item_details'] = $itemDetails;
        }

        Log::info("Midtrans: Membuat Snap Token untuk order {$merchantOrderId}");

        return Snap::getSnapToken($params);
    }

    /**
     * Verifikasi signature dari webhook Midtrans
     *
     * Midtrans mengirim signature SHA-512 dari: orderId + statusCode + grossAmount + serverKey
     * Signature ini WAJIB diverifikasi untuk mencegah webhook palsu.
     *
     * @param string $orderId      Order ID dari payload
     * @param string $statusCode   HTTP status code dari payload
     * @param string $grossAmount  Gross amount dari payload
     * @param string $signatureKey Signature key dari payload
     * @return bool True jika signature valid
     */
    public function verifySignature(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $signatureKey
    ): bool {
        $serverKey = config('services.midtrans.server_key');
        $input     = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = hash("sha512", $input);

        return $signature === $signatureKey;
    }
}

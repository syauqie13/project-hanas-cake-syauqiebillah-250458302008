<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * CheckoutService
 *
 * Menangani seluruh business logic proses checkout:
 * - Membuat order & order items
 * - Mengambil data alamat pengiriman
 * - Integrasi dengan MidtransService untuk generate snap token
 */
class CheckoutService
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Proses checkout dari data yang sudah tervalidasi.
     *
     * @param \App\Models\User $user          User yang melakukan checkout
     * @param array            $validatedData Data dari CheckoutRequest yang sudah valid
     * @return array ['order' => Order, 'snap_token' => string]
     *
     * @throws \Exception Jika terjadi error saat proses checkout
     */
    public function processCheckout($user, array $validatedData): array
    {
        $deliveryType = $validatedData['delivery_type'];
        $store = Store::findOrFail($validatedData['store_id']);

        // Ambil data alamat jika tipe pengiriman = delivery
        $address = null;
        if ($deliveryType === 'delivery' && !empty($validatedData['address_id'])) {
            $address = CustomerAddress::where('id', $validatedData['address_id'])
                ->whereHas('customer', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();

            if (!$address) {
                throw new \Exception('Alamat tidak ditemukan atau bukan milik Anda.');
            }
        }

        $grandTotal = (int) $validatedData['total_belanja'];

        DB::beginTransaction();

        try {
            // Generate merchant order ID unik
            $merchantOrderId = 'HANA-ONL-' . strtoupper(Str::random(6));

            // Buat record Order
            $order = Order::create([
                'user_id'            => $user->id,
                'cashier_id'         => 1, // ID Admin/Sistem default
                'tanggal'            => now(),
                'total'              => $grandTotal,
                'merchant_order_id'  => $merchantOrderId,
                'payment_status'     => 'pending',
                'order_type'         => 'online',
                'delivery_type'      => $deliveryType,
                'status'             => 'pending',

                // Data pengiriman: diambil dari alamat yang dipilih atau data user
                'shipping_name'      => $address->receiver_name ?? $user->name,
                'shipping_phone'     => $address->receiver_phone ?? $user->phone,
                'shipping_email'     => $user->email,
                'shipping_address'   => $address->detail_address ?? null,

                // Simpan info toko yang dipilih
                'shipping_zone_name' => $deliveryType === 'pickup'
                    ? "Pickup di {$store->name}"
                    : "Delivery dari {$store->name}",
                'shipping_price'     => 0, // Ongkir dihitung oleh mekanisme lain
            ]);

            // Simpan setiap item pesanan
            foreach ($validatedData['items'] as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'jumlah'       => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal'     => $item['quantity'] * $item['price'],
                ]);
            }

            // Generate Snap Token dari Midtrans
            $snapToken = $this->midtransService->createSnapToken(
                $merchantOrderId,
                $grandTotal,
                [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                    'phone'      => $user->phone ?? '080000000000',
                ]
            );

            DB::commit();

            Log::info("Checkout berhasil: Order #{$order->id}, Merchant: {$merchantOrderId}");

            return [
                'order'      => $order,
                'snap_token' => $snapToken,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout gagal: " . $e->getMessage());
            throw $e;
        }
    }
}

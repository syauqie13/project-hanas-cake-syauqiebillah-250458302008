<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingZone; // <-- Jangan lupa import ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Checkout Pesanan')]
class CheckoutPage extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $total = 0;

    // Properti Form Pengiriman
    public $name, $email, $phone, $address, $city, $postal_code;

    // --- PROPERTI TAMBAHAN (Harus ada untuk fitur Ongkir) ---
    public $delivery_type = 'delivery';
    public $shipping_zone_id;
    public $shipping_cost = 0;
    public $requires_confirmation = false;
    public $confirmed_shipping = false;
    // -------------------------------------------------------

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ];

        // Validasi tambahan jika delivery
        if ($this->delivery_type == 'delivery') {
            $rules['shipping_zone_id'] = 'required|exists:shipping_zones,id';
            if ($this->requires_confirmation) {
                $rules['confirmed_shipping'] = 'accepted';
            }
        }

        return $rules;
    }

    public function mount()
    {
        // 1. Muat keranjang
        $this->cartItems = session()->get('cart', []);

        if (empty($this->cartItems)) {
            return $this->redirect(route('ecommerce'), navigate: true);
        }

        // 2. Isi otomatis form
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->city = $user->city;
            $this->postal_code = $user->postal_code;
        }

        // 3. Hitung total
        $this->calculateTotal();
    }

    // --- LOGIKA HITUNG ONGKIR (WAJIB ADA) ---
    public function updatedDeliveryType($value)
    {
        if ($value == 'pickup') {
            $this->shipping_zone_id = null;
            $this->shipping_cost = 0;
            $this->requires_confirmation = false;
        }
        $this->calculateTotal();
    }

    public function updatedShippingZoneId($value)
    {
        if ($this->delivery_type == 'delivery') {
            $zone = ShippingZone::find($value);
            if ($zone) {
                $this->shipping_cost = $zone->price;
                $this->requires_confirmation = $zone->requires_confirmation;
            }
        }
        $this->confirmed_shipping = false;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        // Total = Barang + Ongkir
        $this->total = $this->subtotal + $this->shipping_cost;
    }
    // -----------------------------------------

    public function placeOrder()
    {
        $this->validate();

        // Siapkan data Zona
        $zoneName = 'Ambil di Toko (Pickup)';
        $shippingPrice = 0;

        if ($this->delivery_type == 'delivery') {
            $zone = ShippingZone::find($this->shipping_zone_id);
            if ($zone) {
                $zoneName = $zone->name;
                $shippingPrice = $zone->price;
            }
        }

        DB::beginTransaction();
        try {
            // 1️⃣ Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'cashier_id' => 1,
                'tanggal' => now(),
                'total' => (int) $this->total,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'order_type' => 'online',
                'status' => 'pending',

                'shipping_name' => $this->name,
                'shipping_email' => $this->email,
                'shipping_phone' => $this->phone,
                'shipping_address' => $this->address,
                'shipping_city' => $this->city,
                'shipping_postal_code' => $this->postal_code,

                // Simpan data zona & ongkir
                'shipping_zone_name' => $zoneName,
                'shipping_price' => $shippingPrice,
            ]);

            // 2️⃣ Generate invoice
            $merchantOrderId = 'PO-' . $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;
            $order->save();

            Log::info("Membuat order PO pending (Midtrans) ID: {$order->id}");

            // 3️⃣ Loop item & siapkan detail Midtrans
            $item_details_for_midtrans = [];
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product)
                    throw new \Exception("Produk tidak ditemukan.");

                $finalPrice = $product->price - ($product->price * $product->discount / 100);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $finalPrice,
                    'subtotal' => $finalPrice * $item['quantity'],
                ]);

                $item_details_for_midtrans[] = [
                    'id' => $item['product_id'],
                    'price' => (int) $finalPrice,
                    'quantity' => (int) $item['quantity'],
                    'name' => substr($item['name'], 0, 50)
                ];
            }

            // Masukkan Ongkir ke Midtrans (PENTING: agar total cocok)
            if ($shippingPrice > 0) {
                $item_details_for_midtrans[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $shippingPrice,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman'
                ];
            }

            // 4️⃣ Konfigurasi & Parameter
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$is3ds = false;
            Config::$isSanitized = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => (int) $order->total,
                ],
                'customer_details' => [
                    'first_name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'shipping_address' => [ // Kirim alamat ke Midtrans
                        'address' => $this->address,
                        'city' => $this->city,
                        'postal_code' => $this->postal_code,
                    ]
                ],
                'item_details' => $item_details_for_midtrans,
            ];

            // 5️⃣ Minta Snap Token
            $snapToken = Snap::getSnapToken($params);
            DB::commit();

            // 6️⃣ Hapus keranjang
            session()->forget('cart');
            $this->dispatch('cartUpdated');

            // 7️⃣ KIRIM EVENT (Gaya Penulisan Anda)
            // Menggunakan named arguments agar diterima dengan benar oleh JS 'data.snapToken'
            $this->dispatch(
                'snap-show',
                snapToken: $snapToken,
                merchantOrderId: $merchantOrderId
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout Error: {$e->getMessage()}");
            // Tampilkan error menggunakan dispatch notify agar muncul popup
            $this->dispatch('notify', [
                'message' => 'Gagal: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.frontend.checkout-page', [
            // PENTING: Kirim data zona ke view
            'zones' => ShippingZone::all()
        ]);
    }
}

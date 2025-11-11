<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
    public $total = 0;

    // Properti Form Pengiriman
    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $postal_code;

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ];
    }

    public function mount()
    {
        // 1. Muat keranjang dari session
        $this->cartItems = session()->get('cart', []);

        // 2. Jika keranjang kosong, tendang kembali ke toko
        if (empty($this->cartItems)) {
            return $this->redirect(route('shop'), navigate: true);
        }

        // 3. Hitung total
        $this->calculateTotal();

        // 4. Isi otomatis form dengan data user yang login
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        // (Asumsikan Anda menambahkan 'phone', 'address', dll. ke tabel users nanti)
        // $this->phone = $user->phone;
        // $this->address = $user->address;
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cartItems as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    /**
     * Proses checkout dan panggil Midtrans
     */
    public function placeOrder()
    {
        $this->validate(); // Validasi form pengiriman

        DB::beginTransaction();
        try {
            // 1️⃣ Buat Order (dengan status 'pending')
            $order = Order::create([
                // PENTING: Tautkan order ini ke pelanggan yang login
                'user_id' => Auth::id(),

                // Gunakan 'cashier_id' admin/sistem (atau null jika boleh)
                'cashier_id' => 1, // Ganti dengan ID user sistem/admin

                'tanggal' => now(),
                'total' => (int) $this->total,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'order_type' => 'online', // <-- PENTING: Tandai sebagai order 'online'
                'status' => 'pending',

                // Simpan detail pengiriman
                'shipping_name' => $this->name,
                'shipping_email' => $this->email,
                'shipping_phone' => $this->phone,
                'shipping_address' => $this->address,
                'shipping_city' => $this->city,
                'shipping_postal_code' => $this->postal_code,
            ]);

            // 2️⃣ Generate nomor invoice unik untuk Midtrans
            $merchantOrderId = 'PO-' . $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;
            $order->save();

            Log::info("Membuat order PO pending (Midtrans) untuk Order ID: {$order->id}");

            // 3️⃣ Loop item keranjang (dari session), simpan ke DB
            $item_details_for_midtrans = [];
            foreach ($this->cartItems as $item) {
                // Ambil info produk dari DB (untuk keamanan)
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Produk '{$item['name']}' tidak ditemukan.");
                }

                $finalPrice = $product->price - ($product->price * $product->discount / 100);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $finalPrice,
                    'subtotal' => $finalPrice * $item['quantity'],
                ]);

                // Siapkan data untuk dikirim ke Midtrans
                $item_details_for_midtrans[] = [
                    'id' => $item['product_id'],
                    'price' => (int) $finalPrice,
                    'quantity' => (int) $item['quantity'],
                    'name' => $item['name']
                ];
            }

            // 4️⃣ Set Konfig & Parameter Midtrans
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
                    'billing_address' => [ // (Opsional tapi bagus)
                        'address' => $this->address,
                        'city' => $this->city,
                        'postal_code' => $this->postal_code,
                    ],
                ],
                'item_details' => $item_details_for_midtrans,
            ];

            // 5️⃣ Minta Snap Token
            $snapToken = Snap::getSnapToken($params);
            DB::commit();

            // 6️⃣ PERUBAHAN UTAMA: Kosongkan keranjang SESSION
            session()->forget('cart');
            $this->dispatch('cartUpdated'); // Beri tahu ikon keranjang

            // 7️⃣ Kirim token ke JavaScript
            $this->dispatch('snap-show', snapToken: $snapToken);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Kesalahan pada proses checkout PO: {$e->getMessage()}");
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.frontend.checkout-page');
    }
}

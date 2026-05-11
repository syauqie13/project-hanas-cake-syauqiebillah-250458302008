<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Notifications\ResetPinNotification;
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
    public $pin_input;

    // Properti Form Pengiriman
    public $name, $email, $phone, $address, $city, $postal_code;

    // --- PROPERTI ONGKIR ---
    public $delivery_type = 'delivery';
    public $shipping_zone_id;
    public $shipping_cost = 0;
    public $requires_confirmation = false;
    public $confirmed_shipping = false;

    // --- PROPERTI VOUCHER ---
    public $voucherCode = '';
    public $discountAmount = 0;
    public $appliedVoucherId = null;

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
        $this->cartItems = session()->get('cart', []);

        if (empty($this->cartItems)) {
            return $this->redirect(route('ecommerce'), navigate: true);
        }

        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->city = $user->city;
            $this->postal_code = $user->postal_code;
        }

        $this->calculateTotal();
    }

    // --- LOGIKA HITUNG ONGKIR ---
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

    // --- LOGIKA VOUCHER ---

    public function applyVoucher()
    {
        // Jika input manual kosong, berikan error
        if (!$this->voucherCode) {
            $this->addError('voucherCode', 'Masukkan kode voucher terlebih dahulu.');
            return;
        }

        $voucher = \App\Models\Voucher::where('code', $this->voucherCode)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            $this->addError('voucherCode', 'Kode voucher tidak valid.');
            return;
        }

        // Panggil logika untuk memasang voucher (misal ke fungsi internal)
        $this->applyVoucherLogic($voucher);
    }

    private function applyVoucherLogic($voucher)
    {
        // Cek minimal belanja (sesuaikan nama variabel subtotal kamu)
        if ($this->subtotal < $voucher->min_purchase) {
            $this->addError('voucherCode', 'Minimal belanja Rp ' . number_format($voucher->min_purchase) . ' belum tercapai.');
            return;
        }

        $this->appliedVoucherId = $voucher->id;

        // Hitung ulang total belanja kamu (panggil fungsi hitung total yang sudah ada di tokomu)
        $this->calculateTotal();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Voucher ' . $voucher->code . ' berhasil dipasang!'
        ]);
    }
    public function claimVoucher($voucherId)
    {
        if (!Auth::check())
            return;

        UserVoucher::firstOrCreate([
            'user_id' => Auth::id(),
            'voucher_id' => $voucherId,
        ], ['is_used' => false]);

        $this->dispatch('notify', ['message' => 'Voucher berhasil diklaim!', 'icon' => 'success']);
    }

    public function useClaimedVoucher($voucherId)
    {
        $voucher = Voucher::find($voucherId);
        if (!$voucher)
            return;

        if ($voucher->min_purchase && $this->subtotal < $voucher->min_purchase) {
            $this->dispatch('notify', ['message' => 'Minimal belanja belum terpenuhi.', 'icon' => 'error']);
            return;
        }

        if ($voucher->type == 'percentage') {
            $discount = ($this->subtotal * $voucher->value) / 100;
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
            $this->discountAmount = (int) $discount; // Wajib integer
        } else {
            $this->discountAmount = (int) $voucher->value;
        }

        $this->voucherCode = $voucher->code;
        $this->appliedVoucherId = $voucher->id;

        $this->calculateTotal();
        $this->resetErrorBag('voucherCode');
        $this->dispatch('notify', ['message' => 'Voucher berhasil digunakan!', 'icon' => 'success']);
    }

    public function removeVoucher()
    {
        $this->voucherCode = '';
        $this->discountAmount = 0;
        $this->appliedVoucherId = null;
        $this->calculateTotal();
        $this->dispatch('notify', ['message' => 'Voucher dibatalkan.', 'icon' => 'info']);
    }

    // --- PERHITUNGAN TOTAL ---
    public function calculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $finalPrice = (int) ($product->price - ($product->price * $product->discount / 100));
                $this->subtotal += $finalPrice * $item['quantity'];
            }
        }

        // Batalkan voucher otomatis jika subtotal kurang dari minimal
        if ($this->appliedVoucherId) {
            $voucher = Voucher::find($this->appliedVoucherId);
            if ($voucher && $voucher->min_purchase && $this->subtotal < $voucher->min_purchase) {
                $this->removeVoucher();
                return;
            }
        }

        $this->total = $this->subtotal - $this->discountAmount + $this->shipping_cost;
        if ($this->total < 0)
            $this->total = 0;
    }

    // --- EKSEKUSI PEMBAYARAN ---
    public function placeOrder()
    {
        $this->validate();

        // PAKSA HITUNG ULANG DI SINI UNTUK MEMASTIKAN DATA TERBARU
        $this->calculateTotal();

        $zoneName = 'Ambil di Toko (Pickup)';
        $shippingPrice = 0;

        if ($this->delivery_type == 'delivery') {
            $zone = ShippingZone::find($this->shipping_zone_id);
            if ($zone) {
                $zoneName = $zone->name;
                $shippingPrice = (int) $zone->price;
            }
        }

        DB::beginTransaction();
        try {
            // 1. Buat Order Awal dengan TOTAL HASIL CALCULATE TERBARU
            $order = Order::create([
                'user_id' => Auth::id(),
                'voucher_id' => $this->appliedVoucherId,
                'cashier_id' => 1,
                'tanggal' => now(),
                'total' => (int) $this->total, // Gunakan hasil calculateTotal()
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
                'shipping_zone_name' => $zoneName,
                'shipping_price' => $shippingPrice,
            ]);

            $merchantOrderId = 'PO-' . $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;

            // 2. Siapkan Rincian untuk Midtrans
            $item_details_for_midtrans = [];
            $midtransGrossAmount = 0;

            // Masukkan Barang
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product)
                    throw new \Exception("Produk tidak ditemukan.");

                $finalPrice = (int) ($product->price - ($product->price * $product->discount / 100));

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $finalPrice,
                    'subtotal' => $finalPrice * $item['quantity'],
                ]);

                $item_details_for_midtrans[] = [
                    'id' => (string) $item['product_id'],
                    'price' => $finalPrice,
                    'quantity' => (int) $item['quantity'],
                    'name' => substr($item['name'], 0, 50)
                ];
                $midtransGrossAmount += ($finalPrice * $item['quantity']);
            }

            // Masukkan Ongkir
            if ($shippingPrice > 0) {
                $item_details_for_midtrans[] = [
                    'id' => 'SHIPPING',
                    'price' => $shippingPrice,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman'
                ];
                $midtransGrossAmount += $shippingPrice;
            }

            // Masukkan Diskon Voucher (Item Negatif)
            if ($this->discountAmount > 0) {
                $item_details_for_midtrans[] = [
                    'id' => 'VOUCHER',
                    'price' => -((int) $this->discountAmount),
                    'quantity' => 1,
                    'name' => 'Diskon Voucher'
                ];
                $midtransGrossAmount -= (int) $this->discountAmount;
            }

            // UPDATE DATABASE DENGAN HASIL HITUNG ITEM_DETAILS (Agar sinkron dengan Midtrans)
            $order->total = $midtransGrossAmount;
            $order->save();

            Log::info("Membuat order PO pending (Midtrans) ID: {$order->id}. Total DB: {$order->total}. Gross Midtrans: {$midtransGrossAmount}");

            // 3. Konfigurasi Midtrans
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$is3ds = false;
            Config::$isSanitized = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => (int) $midtransGrossAmount, // Harus integer murni
                ],
                'customer_details' => [
                    'first_name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'shipping_address' => [
                        'address' => $this->address,
                        'city' => $this->city,
                        'postal_code' => $this->postal_code,
                    ]
                ],
                'item_details' => $item_details_for_midtrans,
            ];

            // 4. Minta Snap Token
            $snapToken = Snap::getSnapToken($params);
            DB::commit();

            // 5. Bersihkan keranjang
            session()->forget('cart');
            $this->dispatch('cartUpdated');

            $this->dispatch(
                'snap-show',
                snapToken: $snapToken,
                merchantOrderId: $merchantOrderId
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout Error: {$e->getMessage()}");
            $this->dispatch('notify', [
                'message' => 'Gagal: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function validatePinAndPlaceOrder()
    {
        // 1. Validasi input PIN tidak boleh kosong
        $this->validate([
            'pin_input' => 'required|digits:6'
        ], [
            'pin_input.required' => 'PIN wajib diisi.',
            'pin_input.digits' => 'PIN harus 6 digit angka.'
        ]);

        // 2. Cek kecocokan payment_pin di DB
        if (!\Illuminate\Support\Facades\Hash::check($this->pin_input, auth()->user()->payment_pin)) {
            $this->addError('pin_input', 'PIN Pembayaran salah!');
            return;
        }

        // 3. Jika benar, panggil fungsi placeOrder yang sudah kita buat tadi
        // Pastikan di dalam placeOrder() nanti ada perintah tutup modal
        $this->placeOrder();

        // 4. Reset input dan tutup modal
        $this->reset('pin_input');
        $this->dispatch('close-pin-modal');
    }

    public function sendResetPinEmail()
    {
        $user = auth()->user();

        try {
            $user->notify(new ResetPinNotification());

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Link reset PIN sudah dikirim ke Gmail Anda.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Ambil Voucher yang tersedia secara umum (dan belum expired)
        $availableVouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->get();

        // 2. Ambil ID voucher yang SUDAH diklaim oleh user ini (baik yang sudah dipakai atau belum)
        $userVoucherData = UserVoucher::where('user_id', $user->id)->get();

        // ID yang sudah diklaim tapi BELUM dipakai (Status: Bisa Pakai)
        $claimedNotUsedIds = $userVoucherData->where('is_used', false)->pluck('voucher_id')->toArray();

        // ID yang SUDAH dipakai (Status: Harus Hilang dari list)
        $alreadyUsedIds = $userVoucherData->where('is_used', true)->pluck('voucher_id')->toArray();

        return view('livewire.frontend.checkout-page', [
            'zones' => ShippingZone::all(),
            // Filter: Hanya tampilkan voucher yang BELUM pernah dipakai oleh user ini
            'availableVouchers' => $availableVouchers->whereNotIn('id', $alreadyUsedIds),
            'claimedVoucherIds' => $claimedNotUsedIds
        ]);
    }
}
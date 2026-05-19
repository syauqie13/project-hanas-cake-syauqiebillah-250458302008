<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Models\Store;
use App\Models\CustomerAddress;
use App\Notifications\ResetPinNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

    // Data Pelanggan
    public $name, $email, $phone, $address;

    // --- PROPERTI ONGKIR GPS (BARU) ---
    public $delivery_type = 'delivery';
    public $selectedStore = null;
    public $distance = null;
    public $shipping_cost = 0;
    public $isOutOfBounds = false;

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
        ];

        // Validasi khusus pengiriman
        if ($this->delivery_type == 'delivery' || $this->delivery_type == 'po') {
            $rules['address'] = 'required|string|max:500';
            if ($this->isOutOfBounds) {
                // Sengaja dibuat gagal jika di luar jangkauan
                $rules['distance'] = 'numeric|max:10'; 
            }
        }
        return $rules;
    }

    protected $messages = [
        'distance.max' => 'Lokasi pengiriman di luar jangkauan (Maks 5km). Silakan ganti alamat atau metode.',
        'address.required' => 'Pilih alamat pengiriman terlebih dahulu.',
    ];

    public function mount()
    {
        $this->cartItems = Session::get('cart', []);

        if (empty($this->cartItems)) {
            return $this->redirect(route('ecommerce'), navigate: true);
        }

        // Ambil mode yang dipilih di halaman sebelumnya
        $this->delivery_type = Session::get('active_mode', 'pickup');

        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }

        $this->calculateDeliveryCost();
        $this->calculateTotal();
    }

    // --- LOGIKA HITUNG ONGKIR BERBASIS GPS ---
    public function calculateDeliveryCost()
    {
        $this->shipping_cost = 0;
        $this->isOutOfBounds = false;
        $this->distance = null;

        $storeId = Session::get('selected_store_id');
        if ($storeId) {
            $this->selectedStore = Store::find($storeId);
        }

        if (($this->delivery_type == 'delivery' || $this->delivery_type == 'po') && Auth::check()) {
            $customer = Auth::user()->customer;
            $addressId = Session::get('selected_address_id');
            
            // Cari alamat (Prioritas: yang sedang dipilih > yang utama > yang pertama ada)
            $addressModel = CustomerAddress::where('customer_id', $customer->id)->where('id', $addressId)->first()
                    ?? CustomerAddress::where('customer_id', $customer->id)->where('is_primary', true)->first()
                    ?? CustomerAddress::where('customer_id', $customer->id)->first();

            if ($addressModel) {
                $this->address = $addressModel->title . ' - ' . $addressModel->detail_address;

                if ($this->selectedStore && $this->selectedStore->latitude && $addressModel->latitude) {
                    $this->distance = $this->calculateDistance(
                        $addressModel->latitude, 
                        $addressModel->longitude, 
                        $this->selectedStore->latitude, 
                        $this->selectedStore->longitude
                    );

                    if ($this->distance > 10) {
                        $this->isOutOfBounds = true;
                        $this->shipping_cost = 0;
                    } else {
                        $this->isOutOfBounds = false;
                        // Tarif: <= 1km = 2k, selebihnya kelipatan 2k/km
                        $this->shipping_cost = $this->distance <= 1 ? 2000 : ceil($this->distance) * 2000;
                    }
                }
            } else {
                $this->address = null;
            }
        } else {
            $this->address = 'Ambil langsung di Toko/Store';
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371;
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLon = deg2rad((float)$lon2 - (float)$lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }

    public function updatedDeliveryType($value)
    {
        Session::put('active_mode', $value);
        $this->calculateDeliveryCost();
        $this->calculateTotal();
    }

    // --- LOGIKA VOUCHER ---
    public function applyVoucher()
    {
        if (!$this->voucherCode) {
            $this->addError('voucherCode', 'Masukkan kode voucher terlebih dahulu.');
            return;
        }

        $voucher = Voucher::where('code', $this->voucherCode)->where('is_active', true)->first();

        if (!$voucher) {
            $this->addError('voucherCode', 'Kode voucher tidak valid.');
            return;
        }

        $this->applyVoucherLogic($voucher);
    }

    private function applyVoucherLogic($voucher)
    {
        if ($this->subtotal < $voucher->min_purchase) {
            $this->addError('voucherCode', 'Minimal belanja Rp ' . number_format($voucher->min_purchase) . ' belum tercapai.');
            return;
        }

        $this->appliedVoucherId = $voucher->id;
        $this->calculateTotal();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Voucher ' . $voucher->code . ' berhasil dipasang!']);
    }

    public function claimVoucher($voucherId)
    {
        if (!Auth::check()) return;

        UserVoucher::firstOrCreate([
            'user_id' => Auth::id(),
            'voucher_id' => $voucherId,
        ], ['is_used' => false]);

        $this->dispatch('notify', ['message' => 'Voucher berhasil diklaim!', 'icon' => 'success']);
    }

    public function useClaimedVoucher($voucherId)
    {
        $voucher = Voucher::find($voucherId);
        if (!$voucher) return;

        if ($voucher->min_purchase && $this->subtotal < $voucher->min_purchase) {
            $this->dispatch('notify', ['message' => 'Minimal belanja belum terpenuhi.', 'icon' => 'error']);
            return;
        }

        if ($voucher->type == 'percentage') {
            $discount = ($this->subtotal * $voucher->value) / 100;
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
            $this->discountAmount = (int) $discount;
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

        if ($this->appliedVoucherId) {
            $voucher = Voucher::find($this->appliedVoucherId);
            if ($voucher && $voucher->min_purchase && $this->subtotal < $voucher->min_purchase) {
                $this->removeVoucher();
                return;
            }
        }

        $this->total = $this->subtotal - $this->discountAmount + $this->shipping_cost;
        if ($this->total < 0) $this->total = 0;
    }

    // --- EKSEKUSI PEMBAYARAN MIDTRANS ---
    public function placeOrder()
    {
        $this->validate();
        $this->calculateTotal();

        $storeName = $this->selectedStore ? $this->selectedStore->name : 'Hana\'s Cake Store';
        $shippingPrice = (int) $this->shipping_cost;
        $invoiceZoneName = ($this->delivery_type == 'pickup') ? 'Ambil di Toko' : "Kirim Berbasis Jarak ({$this->distance}km)";

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'voucher_id' => $this->appliedVoucherId,
                'cashier_id' => 1,
                'tanggal' => now(),
                'total' => (int) $this->total,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'order_type' => 'online',
                'delivery_type' => $this->delivery_type,
                'status' => 'pending',
                'shipping_name' => $this->name,
                'shipping_email' => $this->email,
                'shipping_phone' => $this->phone,
                'shipping_address' => $this->delivery_type != 'pickup' ? $this->address : null,
                'shipping_city' => $storeName, 
                'shipping_postal_code' => null,
                'shipping_zone_name' => $invoiceZoneName,
                'shipping_price' => $shippingPrice,
            ]);

            $merchantOrderId = 'PO-' . $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;

            $item_details_for_midtrans = [];
            $midtransGrossAmount = 0;

            foreach ($this->cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) throw new \Exception("Produk tidak ditemukan.");

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

            if ($shippingPrice > 0) {
                $item_details_for_midtrans[] = [
                    'id' => 'SHIPPING_KM',
                    'price' => $shippingPrice,
                    'quantity' => 1,
                    'name' => "Ongkir Kurir ({$this->distance}km)"
                ];
                $midtransGrossAmount += $shippingPrice;
            }

            if ($this->discountAmount > 0) {
                $item_details_for_midtrans[] = [
                    'id' => 'VOUCHER',
                    'price' => -((int) $this->discountAmount),
                    'quantity' => 1,
                    'name' => 'Diskon Voucher'
                ];
                $midtransGrossAmount -= (int) $this->discountAmount;
            }

            $order->total = $midtransGrossAmount;
            $order->save();

            Log::info("Membuat order pending (Midtrans) ID: {$order->id}. Gross Midtrans: {$midtransGrossAmount}");

            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$is3ds = false;
            Config::$isSanitized = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => (int) $midtransGrossAmount,
                ],
                'customer_details' => [
                    'first_name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'shipping_address' => [
                        'address' => $this->address ?? 'Toko',
                    ]
                ],
                'item_details' => $item_details_for_midtrans,
            ];

            $snapToken = Snap::getSnapToken($params);
            DB::commit();

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
            $this->dispatch('notify', ['message' => 'Gagal: ' . $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function validatePinAndPlaceOrder()
    {
        $this->validate([
            'pin_input' => 'required|digits:6'
        ], [
            'pin_input.required' => 'PIN wajib diisi.',
            'pin_input.digits' => 'PIN harus 6 digit angka.'
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($this->pin_input, auth()->user()->payment_pin)) {
            $this->addError('pin_input', 'PIN Pembayaran salah!');
            return;
        }

        $this->placeOrder();
        $this->reset('pin_input');
        $this->dispatch('close-pin-modal');
    }

    public function sendResetPinEmail()
    {
        $user = auth()->user();
        try {
            $user->notify(new ResetPinNotification());
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Link reset PIN sudah dikirim ke Gmail Anda.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $user = Auth::user();

        $availableVouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })->get();

        $userVoucherData = UserVoucher::where('user_id', $user->id)->get();
        $claimedNotUsedIds = $userVoucherData->where('is_used', false)->pluck('voucher_id')->toArray();
        $alreadyUsedIds = $userVoucherData->where('is_used', true)->pluck('voucher_id')->toArray();

        return view('livewire.frontend.checkout-page', [
            'availableVouchers' => $availableVouchers->whereNotIn('id', $alreadyUsedIds),
            'claimedVoucherIds' => $claimedNotUsedIds
        ]);
    }
}
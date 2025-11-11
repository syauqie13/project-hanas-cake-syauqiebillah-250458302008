<?php

namespace App\Livewire\Karyawan\Pos;

use Midtrans\Snap;
use Midtrans\Config;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\OrderItem;
use App\Models\ProductRecipe;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import
use Livewire\Attributes\On;         // 1. Pastikan On di-import

#[Layout('components.layouts.pos')]
class PosComponent extends Component
{

    // ... (Semua properti Anda: $search, $cart, $total, dll. biarkan saja) ...
    public $search = '';
    public $selectedCategory = null;
    public $cart = [];
    public $total = 0;
    public $paid_amount = null;
    public $change_amount = 0;
    public $payment_method = 'tunai';
    public $payment_status = 'paid';
    public $customerSearch = '';
    public $customerResults = [];
    public $selectedCustomerId = null;
    public $selectedCustomerName = null;
    protected $listeners = ['customerCreated'];


    public function mount(Request $request)
    {
        // Logika 'mount' Anda untuk menangani redirect URL (jika terjadi)
        // Ini sudah benar, biarkan saja.
        if ($request->has('transaction_status')) {
            $status = $request->query('transaction_status');
            $orderId = $request->query('order_id');

            if ($status == 'settlement' || $status == 'capture') {
                session()->flash('success', "Pembayaran untuk Order ID: $orderId telah berhasil!");
            } else if ($status == 'pending') {
                session()->flash('info', "Pembayaran untuk Order ID: $orderId sedang tertunda (pending).");
            } else if ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
                session()->flash('error', "Pembayaran untuk Order ID: $orderId gagal atau dibatalkan.");
            }
        }
        $this->calculateTotal();
    }

    // ... (Semua fungsi lain Anda: render, addToCart, dll. biarkan saja) ...
    public function render()
    {
        // Ambil produk berdasarkan pencarian dan kategori
        $products = Product::query() // Mulai query
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->orderBy('stock', 'desc') // <-- TAMBAHAN: Urutkan stok 0 di akhir
            ->orderBy('name', 'asc')   // Urutkan berdasarkan nama
            ->get(); // Ambil semua produk

        $categories = Category::all();

        return view('livewire.karyawan.pos.pos-component', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    public function create()
    {
        $this->dispatch('openCreateModal');
    }
    public function updatedCustomerSearch($value)
    {
        if (strlen($value) < 2) {
            $this->customerResults = [];
            return;
        }
        $this->customerResults = Customer::where('name', 'like', '%' . $value . '%')
            ->orWhere('phone', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }
    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->selectedCustomerId = $customer->id;
            $this->selectedCustomerName = $customer->name;
            $this->customerSearch = '';
            $this->customerResults = [];
        }
    }
    public function clearCustomer()
    {
        $this->selectedCustomerId = null;
        $this->selectedCustomerName = null;
    }
    public function customerCreated($customerId)
    {
        $this->selectCustomer($customerId);
    }
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product)
            return;

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['jumlah'] < $product->stock) {
                $this->cart[$productId]['jumlah']++;
            }
        } else {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price - ($product->price * $product->discount / 100),
                'discount' => $product->discount,
                'stock' => $product->stock,
                'jumlah' => 1,
            ];
        }

        $this->calculateTotal();
        $this->updatedPaidAmount($this->paid_amount); // Update kembalian
    }
    public function updateCartQuantity($productId, $jumlah)
    {
        if (isset($this->cart[$productId])) {
            $product = Product::find($productId);
            if ($jumlah > $product->stock) {
                $this->cart[$productId]['jumlah'] = $product->stock;
                session()->flash('error', 'Stok tidak mencukupi.');
            } elseif ($jumlah <= 0) {
                $this->removeFromCart($productId);
            } else {
                $this->cart[$productId]['jumlah'] = $jumlah;
            }
        }
        $this->calculateTotal();
        $this->updatedPaidAmount($this->paid_amount); // Update kembalian
    }
    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
        $this->updatedPaidAmount($this->paid_amount); // Update kembalian
    }
    public function clearCart()
    {
        $this->cart = [];
        $this->paid_amount = 0;
        $this->change_amount = 0;
        $this->total = 0;
        $this->selectedCustomerId = null;
        $this->selectedCustomerName = null;
        $this->payment_method = 'tunai';
    }
    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['jumlah'];
        }
    }
    public function updatedPaidAmount($value)
    {
        $paid = (is_numeric($value)) ? (float) $value : 0;
        if ($paid >= $this->total) {
            $this->change_amount = $paid - $this->total;
        } else {
            $this->change_amount = 0;
        }
    }
    public function processPayment()
    {
        // Validasi dasar
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }
        if ($this->total <= 0) {
            session()->flash('error', 'Total belanja tidak valid.');
            return;
        }

        if ($this->payment_method == 'tunai') {
            $this->prosesPembayaranTunai();
        } else {
            $this->prosesPembayaranDigitalMidtrans();
        }
    }


    public function prosesPembayaranTunai()
    {
        if ($this->paid_amount < $this->total) {
            $this->payment_status = 'not_paid';
            session()->flash('error', 'Jumlah bayar kurang dari total.');
            return;
        } else {
            $this->payment_status = 'paid';
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_id' => $this->selectedCustomerId,
                'cashier_id' => Auth::id(),
                'tanggal' => now(),
                'total' => $this->total,
                'paid_amount' => $this->paid_amount,
                'change_amount' => $this->change_amount,
                'payment_method' => 'tunai',
                'payment_status' => $this->payment_status,
                'order_type' => 'pos',
                'status' => 'completed',
            ]);

            $runningNumber = str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $invoiceNumber = 'POS-' . date('Ym') . '-' . $runningNumber;
            $order->merchant_order_id = $invoiceNumber;
            $order->save();

            Log::info("Memulai pengurangan stok untuk Order ID: {$order->id}");

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['jumlah'],
                ]);
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                if ($product) {
                    if ($product->stock < $item['jumlah']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }
                    $product->decrement('stock', $item['jumlah']);
                }
                $recipes = ProductRecipe::where('product_id', $item['product_id'])->get();
                foreach ($recipes as $recipe) {
                    $inventoryItem = Inventory::where('id', $recipe->inventory_id)->lockForUpdate()->first();
                    if ($inventoryItem) {
                        $quantityToReduce = $recipe->quantity_used * $item['jumlah'];
                        if ($inventoryItem->stock < $quantityToReduce) {
                            throw new \Exception("Stok bahan {$inventoryItem->name} tidak mencukupi.");
                        }
                        $inventoryItem->decrement('stock', $quantityToReduce);
                    }
                }
            }

            DB::commit();

            $this->dispatch('notify', ['message' => 'Transaksi (Tunai) berhasil disimpan!']);
            // Memicu print struk untuk Tunai
            $this->dispatch('print-receipt', orderId: $order->id);
            $this->clearCart();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Kesalahan pada proses pembayaran tunai: {$e->getMessage()}");
            session()->flash('error', 'Terjadi kesalahan (Tunai): ' . $e->getMessage());
        }
    }



    public function prosesPembayaranDigitalMidtrans()
    {
        DB::beginTransaction();
        try {
            // ... (1️⃣ Buat Order) ...
            $order = Order::create([
                'customer_id' => $this->selectedCustomerId,
                'cashier_id' => Auth::id(),
                'tanggal' => now(),
                'total' => (int) $this->total,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'order_type' => 'pos',
                'status' => 'pending',
            ]);
            // ... (Generate nomor invoice) ...
            $merchantOrderId = $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;
            $order->save();

            Log::info("Membuat order pending (Midtrans) untuk Order ID: {$order->id}");

            // ... (2️⃣ Loop item, simpan OrderItem, siapkan $item_details_for_midtrans) ...
            $item_details_for_midtrans = [];
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['jumlah'],
                ]);
                $item_details_for_midtrans[] = [
                    'id' => $item['product_id'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['jumlah'],
                    'name' => $item['name'] ?? 'Produk'
                ];
            }

            // ... (3️⃣ & 4️⃣ Set Konfig & Parameter Midtrans) ...
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$is3ds = false;
            Config::$isSanitized = true;
            $params = [
                'transaction_details' => ['order_id' => $merchantOrderId, 'gross_amount' => (int) $this->total,],
                'customer_details' => [
                    'first_name' => $this->selectedCustomerName ?? 'Guest',
                    'email' => Customer::find($this->selectedCustomerId)->email ?? 'guest@hanacake.com',
                    'phone' => Customer::find($this->selectedCustomerId)->phone ?? '08123456789',
                ],
                'item_details' => $item_details_for_midtrans,
            ];

            // 5️⃣ Minta Snap Token
            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            // 6️⃣ PERUBAHAN UTAMA: Kosongkan keranjang SEKARANG
            $this->clearCart();

            // 7️⃣ (INI KUNCINYA) Kirim token ke frontend
            $this->dispatch('snap-show', snapToken: $snapToken, merchantOrderId: $merchantOrderId);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Kesalahan pada proses pembayaran digital: {$e->getMessage()}");
            session()->flash('error', 'Terjadi kesalahan (Midtrans): ' . $e->getMessage());
        }
    }

    #[On('paymentSuccess')]
    public function paymentSuccess($result)
    {
        // $result berisi data dari Midtrans (misal: $result['order_id'])
        Log::info("Pembayaran digital (Midtrans) Sukses via Snap Callback. Order ID: " . $result['order_id']);

        // Tindakan yang ditunda: Kosongkan keranjang dan beri notifikasi
        $this->clearCart();
        $this->dispatch('notify', ['message' => 'Pembayaran digital berhasil!']);
    }

    /**
     * (Opsional) Listener jika pembayaran pending (misal: VA)
     */
    #[On('paymentPending')]
    public function paymentPending($result)
    {
        Log::info("Pembayaran digital (Midtrans) Pending via Snap Callback. Order ID: " . $result['order_id']);
        $this->clearCart();
        $this->dispatch('notify', ['message' => 'Menunggu pembayaran...']);
    }

    /**
     * (Opsional) Listener jika pembayaran error
     */
    #[On('paymentError')]
    public function paymentError($result)
    {
        Log::warning("Pembayaran digital (Midtrans) Gagal via Snap Callback. Error: " . $result['status_message']);
        // JANGAN clearCart() agar kasir bisa coba lagi
        session()->flash('error', 'Pembayaran Gagal: ' . $result['status_message']);
    }
}

<?php

namespace App\Livewire\Karyawan\Pos;

use Midtrans\Snap;
use Midtrans\Config;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Order;    // model dari tabel orders
use App\Models\Product;  // model dari tabel products
use App\Models\Category; // model dari tabel categories
use App\Models\Inventory; // model dari tabel inventories
use App\Models\OrderItem; // model dari tabel order_items
use App\Models\ProductRecipe; // model dari tabel product_recipes

#[Layout('components.layouts.pos')]
class PosComponent extends Component
{

    // Properti untuk state
    public $search = '';
    public $selectedCategory = null;
    public $cart = []; // Ini akan menyimpan keranjang belanja

    // Properti untuk pembayaran
    public $total = 0;
    public $paid_amount = null;
    public $change_amount = 0;
    public $payment_method = 'tunai'; // Default 'tunai'
    public $payment_status = 'paid';
    public $customerSearch = '';
    public $customerResults = [];
    public $selectedCustomerId = null;
    public $selectedCustomerName = null;

    // Listener (Hanya untuk customerCreated)
    protected $listeners = ['customerCreated'];

    public function mount(Request $request)
    {
        // 2. TAMBAHKAN SELURUH FUNGSI 'mount()' INI

        // Cek apakah ada parameter 'transaction_status' di URL
        if ($request->has('transaction_status')) {
            $status = $request->query('transaction_status');
            $orderId = $request->query('order_id');

            if ($status == 'settlement' || $status == 'capture') {
                // Jika pembayaran sukses
                session()->flash('success', "Pembayaran untuk Order ID: $orderId telah berhasil!");
            } else if ($status == 'pending') {
                // Jika masih pending (misal: bayar di Indomaret)
                session()->flash('info', "Pembayaran untuk Order ID: $orderId sedang tertunda (pending).");
            } else if ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
                // Jika gagal, dibatalkan, atau kedaluwarsa
                session()->flash('error', "Pembayaran untuk Order ID: $orderId gagal atau dibatalkan.");
            }
        }

        // ... sisa logika mount Anda jika ada (misal: inisialisasi cart) ...
        // $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    // Fungsi ini dipanggil setiap kali ada perubahan pada properti
    public function render()
    {
        // Ambil produk berdasarkan pencarian dan kategori
        $products = Product::where('stock', '>', 0)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->get();

        $categories = Category::all();

        // Kita TIDAK perlu panggil calculateTotal() dan calculateChange() di sini
        // karena akan dipanggil oleh method lain saat dibutuhkan (add, update, remove cart)
        // Ini membuat performa lebih cepat.

        return view('livewire.karyawan.pos.pos-component', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    // --- Manajemen Customer ---

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

    // --- Manajemen Keranjang (Cart) ---

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
        $this->change_amount = 0; // Reset kembalian
        $this->total = 0;         // Reset total
        $this->selectedCustomerId = null;
        $this->selectedCustomerName = null;
        $this->payment_method = 'tunai'; // Reset ke default
    }

    // --- Logika Perhitungan ---

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['jumlah'];
        }
    }

    // (calculateChange() diganti dengan ini agar lebih reaktif)
    // Dipanggil otomatis setiap $paid_amount berubah (jika pakai wire:model.live)
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
        // ... (validasi keranjang) ...

        if ($this->payment_method == 'tunai') {
            $this->prosesPembayaranTunai();
        } else {
            $this->prosesPembayaranDigitalMidtrans();
        }
    }

    /**
     * LOGIKA UNTUK PEMBAYARAN TUNAI
     * (Ini adalah kode lama Anda yang dipindahkan ke method-nya sendiri)
     */
    public function prosesPembayaranTunai()
    {
        // 1️⃣ Validasi jumlah bayar
        if ($this->paid_amount < $this->total) {
            $this->payment_status = 'not_paid';
            session()->flash('error', 'Jumlah bayar kurang dari total.');
            return;
        } else {
            $this->payment_status = 'paid';
        }

        DB::beginTransaction();
        try {
            // 2️⃣ Buat Order
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

            // Generate nomor invoice
            $runningNumber = str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $invoiceNumber = 'POS-' . date('Ym') . '-' . $runningNumber;
            $order->merchant_order_id = $invoiceNumber;
            $order->save();

            Log::info("Memulai pengurangan stok untuk Order ID: {$order->id}");

            // 3️⃣ Loop semua item di cart
            foreach ($this->cart as $item) {
                // Simpan ke order items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['jumlah'],
                ]);

                // 4️⃣ Kurangi stok produk utama (jika ada kolom stock di products)
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                if ($product) {
                    if ($product->stock < $item['jumlah']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }

                    $product->decrement('stock', $item['jumlah']);
                    Log::info("Stok produk {$product->name} dikurangi {$item['jumlah']} (Sisa: {$product->stock})");
                }

                // 5️⃣ Kurangi stok bahan baku (inventories) berdasarkan resep
                $recipes = ProductRecipe::where('product_id', $item['product_id'])->get();
                foreach ($recipes as $recipe) {
                    $inventoryItem = Inventory::where('id', $recipe->inventory_id)->lockForUpdate()->first();
                    if ($inventoryItem) {
                        $quantityToReduce = $recipe->quantity_used * $item['jumlah'];

                        if ($inventoryItem->stock < $quantityToReduce) {
                            throw new \Exception("Stok bahan {$inventoryItem->name} tidak mencukupi untuk produk {$item['name']}.");
                        }

                        $inventoryItem->decrement('stock', $quantityToReduce);
                        Log::info("Stok bahan {$inventoryItem->name} dikurangi {$quantityToReduce} (Sisa: {$inventoryItem->stock})");
                    }
                }
            }

            // 6️⃣ Commit transaksi
            DB::commit();

            $this->dispatch('notify', ['message' => 'Transaksi (Tunai) berhasil disimpan!']);
            $this->clearCart(); // Kosongkan keranjang

            // (Opsional) Dispatch event print struk
            // $this->dispatch('print-receipt', orderId: $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Kesalahan pada proses pembayaran tunai: {$e->getMessage()}");
            session()->flash('error', 'Terjadi kesalahan (Tunai): ' . $e->getMessage());
        }
    }



    /**
     * LOGIKA UNTUK PEMBAYARAN DIGITAL (DUITKU)
     * (Ini adalah kode DuitKu Anda, tapi sudah di-update agar konsisten)
     */
    public function prosesPembayaranDigitalMidtrans()
    {
        DB::beginTransaction();
        try {
            // 1. Buat Order (status 'pending')
            $order = Order::create([
                'customer_id' => $this->selectedCustomerId,
                'cashier_id' => Auth::id(),
                'tanggal' => now(),
                'total' => (int) $this->total, // Pastikan integer
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => 'midtrans', // Placeholder
                'payment_status' => 'pending',
                'order_type' => 'pos',
                'status' => 'pending',
            ]);

            // Buat Order ID unik untuk Midtrans
            $merchantOrderId = $order->id . '-' . time();
            $order->merchant_order_id = $merchantOrderId;
            $order->save();

            // 2. Buat Order Items (sama seperti DuitKu)
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['jumlah'],
                ]);
            }

            // 3. Set Konfigurasi Midtrans
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$is3ds = false;
            Config::$isSanitized = true;

            // 4.a Siapkan Rincian Item untuk Midtrans
            $item_details = [];
            foreach ($this->cart as $item) {

                $item_details[] = [
                    'id' => $item['product_id'],
                    'price' => (int) $item['price'],       // Pastikan integer
                    'quantity' => (int) $item['jumlah'], // Pastikan integer
                    'name' => $item['name'] ?? 'Produk' // Ganti 'name' sesuai struktur data cart Anda
                ];
            }

            // 4.b Buat Parameter Transaksi (LENGKAP)
            $params = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => (int) $order->total,
                ],
                'customer_details' => [
                    'first_name' => $this->selectedCustomerName ?? 'Guest',
                    // Ambil data customer sekali saja untuk efisiensi
                    'email' => Customer::find($this->selectedCustomerId)->email ?? 'guest@hanacake.com',
                    'phone' => Customer::find($this->selectedCustomerId)->phone ?? '08123456789',
                ],
                // INI BAGIAN YANG PENTING
                'item_details' => $item_details,
            ];

            // 5. Minta Snap Token (HANYA SEKALI)
            $snapToken = Snap::getSnapToken($params);

            // Jika berhasil, commit order 'pending'
            DB::commit();

            // 6. Reset keranjang
            $this->clearCart();

            // 7. (INI KUNCINYA) Kirim token ke frontend
            $this->dispatch('snap-show', snapToken: $snapToken);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan (Midtrans): ' . $e->getMessage());
        }
    }
}

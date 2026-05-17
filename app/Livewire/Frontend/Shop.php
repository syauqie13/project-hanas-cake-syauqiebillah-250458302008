<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category; // 1. Tambahkan Model Category
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination; // 2. Tambahkan WithPagination

#[Layout('components.layouts.ecommerce')]
#[Title('Selamat Datang di Toko PO Kami')]
class Shop extends Component
{
    use WithPagination; // 3. Gunakan Trait Pagination

    // ===================================
    public $search = '';
    public $selectedCategory = null; // atau ''
    // ===================================

    public $mode = 'po';
    public $selectedStore = null;
    public $distance = null;
    public $eta = null;
    public $isOutOfBounds = false;
    public $detailAddress = '';
    public $activeAddressTitle = '';

    // Detail Produk Modal
    public $selectedProductForDetail = null;
    public $selectedFlavor = null;
    public $selectedPortion = null;

    public function openProductDetail($id)
    {
        $this->selectedProductForDetail = Product::findOrFail($id);
        
        // Reset pilihan setiap kali buka modal
        $this->selectedFlavor = null;
        $this->selectedPortion = null;
        
        // Jika hanya ada 1 pilihan, otomatis pilihkan
        if ($this->selectedProductForDetail->flavors && count($this->selectedProductForDetail->flavors) == 1) {
            $this->selectedFlavor = $this->selectedProductForDetail->flavors[0];
        }
        if ($this->selectedProductForDetail->portions && count($this->selectedProductForDetail->portions) == 1) {
            $this->selectedPortion = $this->selectedProductForDetail->portions[0];
        }

        $this->dispatch('open-product-modal');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }

    public function mount()
    {
        $this->mode = request()->query('mode', 'po');
        if (!in_array($this->mode, ['po', 'pickup', 'delivery'])) {
            $this->mode = 'po';
        }

        // Setup Selected Store
        $storeId = session('selected_store_id');
        if ($storeId) {
            $this->selectedStore = \App\Models\Store::find($storeId);
        }
        
        if (!$this->selectedStore) {
            $this->selectedStore = \App\Models\Store::where('is_active', true)->first();
            if ($this->selectedStore) {
                session()->put('selected_store_id', $this->selectedStore->id);
            }
        }

        // Setup Location & Distance
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->customer) {
            $customer = \Illuminate\Support\Facades\Auth::user()->customer;
            
            $addressId = \Illuminate\Support\Facades\Session::get('selected_address_id');
            $address = null;
            
            if ($addressId) {
                $address = \App\Models\CustomerAddress::where('customer_id', $customer->id)->where('id', $addressId)->first();
            }
            
            if (!$address) {
                $address = \App\Models\CustomerAddress::where('customer_id', $customer->id)->where('is_primary', true)->first();
                if (!$address) {
                    $address = \App\Models\CustomerAddress::where('customer_id', $customer->id)->first();
                }
                
                if ($address) {
                    \Illuminate\Support\Facades\Session::put('selected_address_id', $address->id);
                }
            }

            if ($address) {
                $this->detailAddress = $address->detail_address ?? '';
                $this->activeAddressTitle = $address->title ?? '';
                
                if ($this->selectedStore && $this->selectedStore->latitude && $address->latitude) {
                    $this->distance = $this->calculateDistance($address->latitude, $address->longitude, $this->selectedStore->latitude, $this->selectedStore->longitude);
                    
                    if ($this->mode == 'delivery') {
                        if ($this->distance > 5) {
                            $this->isOutOfBounds = true;
                        } else {
                            $this->eta = round($this->distance * 3 + 15); // Simple ETA calculation
                        }
                    }
                }
            }
        }
    }

    #[\Livewire\Attributes\On('location-updated')]
    public function updateLocation($lat, $lng)
    {
        // Fitur ini digantikan dengan halaman AddressSelection.
        // Jika dibutuhkan, arahkan user ke halaman Tambah Alamat
    }

    public function updatedDetailAddress($value)
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->customer) {
            $addressId = \Illuminate\Support\Facades\Session::get('selected_address_id');
            if ($addressId) {
                \App\Models\CustomerAddress::where('id', $addressId)
                    ->where('customer_id', \Illuminate\Support\Facades\Auth::user()->customer->id)
                    ->update(['detail_address' => $value]);
            }
        }
    }

    /**
     * (Fungsi 'addToCart' Anda yang sudah ada)
     */
    public function addToCart($productId, $isFromDetail = false)
    {
        $product = Product::findOrFail($productId);

        if ($this->mode == 'po' && (!$product->is_po || $product->po_deadline->isPast() || $product->stock <= 0)) {
            $this->dispatch('notify', [
                'message' => 'Produk ini sudah tidak tersedia untuk PO.',
                'icon' => 'error'
            ]);
            return;
        }

        if (in_array($this->mode, ['pickup', 'delivery']) && $product->stock <= 0) {
             $this->dispatch('notify', [
                'message' => 'Produk ini tidak tersedia untuk ready stock.',
                'icon' => 'error'
            ]);
            return;
        }

        // --- VALIDASI PILIHAN RASA & PORSI ---
        $flavor = null;
        $portion = null;

        if ($isFromDetail) {
            // Jika tambah dari modal, cek apakah sudah pilih rasa/porsi
            if (!empty($product->flavors) && empty($this->selectedFlavor)) {
                $this->dispatch('notify', ['message' => 'Silakan pilih rasa terlebih dahulu.', 'icon' => 'warning']);
                return;
            }
            if (!empty($product->portions) && empty($this->selectedPortion)) {
                $this->dispatch('notify', ['message' => 'Silakan pilih porsi terlebih dahulu.', 'icon' => 'warning']);
                return;
            }
            $flavor = $this->selectedFlavor;
            $portion = $this->selectedPortion;
        } else {
            // Jika tambah cepat (tombol + di luar modal)
            // Cek apakah produk ini PUNYA variasi
            if (!empty($product->flavors) || !empty($product->portions)) {
                // Jangan masukkan keranjang, paksa buka modal!
                $this->openProductDetail($productId);
                return;
            }
        }

        $cart = session()->get('cart', []);

        // Buat Cart Key Unik
        $cartKey = $product->id;
        if ($flavor || $portion) {
            $cartKey .= '_' . md5($flavor . $portion);
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price - ($product->price * $product->discount / 100),
                "image" => $product->image,
                "flavor" => $flavor,
                "portion" => $portion
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('notify', [
            'message' => $product->name . ' berhasil ditambahkan ke keranjang!',
            'icon' => 'success'
        ]);

        if ($isFromDetail) {
            $this->dispatch('close-product-modal'); // Dispatch event ke AlpineJS untuk tutup modal
        }

        $this->dispatch('cartUpdated');
    }

    /**
     * PERBAIKAN: Reset paginasi saat 'search' atau 'selectedCategory' berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->mode == 'po', function($q) {
                // Mode PO: Hanya tampilkan barang yang di-set PO dan deadline belum lewat
                $q->where('is_po', true)
                  ->where('po_deadline', '>', now());
            })
            ->when(in_array($this->mode, ['pickup', 'delivery']), function($q) {
                // Mode Pickup/Delivery (Ready): Bebas barang apa saja (PO atau Bukan), 
                // ASALKAN punya stok fisik > 0.
            })
            ->where('stock', '>', 0)

            // ===================================
            // === 5. QUERY FILTER DITAMBAHKAN ===
            // ===================================
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            // ===================================

            ->latest()
            ->paginate(12); // Pastikan menggunakan paginate

        // ===================================
        // === 6. AMBIL DATA KATEGORI ===
        // ===================================
        $categories = Category::orderBy('name')->get();

        return view('livewire.frontend.shop', [
            'products' => $products,
            'categories' => $categories // Kirim kategori ke view
        ]);
    }
}

<?php

namespace App\Livewire\Shared\Product;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // <-- Saya ganti ke 'Rule' untuk validasi unik yang lebih bersih

class ProductEdit extends Component
{
    use WithFileUploads;

    public $productId;
    public $name, $price, $categoryId, $discount, $stock, $slug, $description;
    public $flavors = '';
    public $portions = '';
    public $image, $old_image;

    // public $categories = []; // <-- Dihapus, kita akan ambil di render()

    // --- PROPERTI PO DITAMBAHKAN ---
    public $is_po = false;
    public $po_deadline;
    // ---------------------------------

    protected $listeners = ['openEditModal' => 'loadData'];

    public function render()
    {
        // PERBAIKAN: Muat kategori di sini agar selalu tersedia
        $categories = Category::orderBy('name')->get();

        return view('livewire.shared.product.product-edit', [
            'categories' => $categories,
        ]);
    }

    public function loadData($id)
    {
        $product = Product::findOrFail($id);

        // Isi semua field sesuai data produk
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->price = $product->price;
        $this->discount = $product->discount;
        $this->stock = $product->stock;
        $this->categoryId = $product->category_id; // Sesuaikan nama variabel
        $this->old_image = $product->image;
        $this->image = null; // Pastikan file upload sebelumnya tidak terbawa saat membuka modal edit baru
        $this->description = $product->description;

        $this->flavors = is_array($product->flavors) ? implode(', ', $product->flavors) : '';
        $this->portions = is_array($product->portions) ? implode(', ', $product->portions) : '';

        // --- DATA PO DIMUAT DI SINI ---
        $this->is_po = $product->is_po;
        $this->po_deadline = $product->po_deadline ? $product->po_deadline->format('Y-m-d') : null;
        // --------------------------------

        // (Query kategori dipindahkan ke render())

        $this->resetErrorBag(); // Hapus error validasi sebelumnya
        // Tampilkan modal edit
        $this->dispatch('showEditModal');
    }

    public function updatedName()
    {
        // Slug otomatis
        $this->slug = Str::slug($this->name);
    }

    public function update()
    {
        $this->validate([
            'name' => [
                'required',
                'min:3',
                // Gunakan Rule::unique() agar lebih mudah dibaca
                Rule::unique('products')->ignore($this->productId),
            ],
            'slug' => [
                'required',
                Rule::unique('products')->ignore($this->productId),
            ],
            'categoryId' => 'required|exists:categories,id', // <-- Disesuaikan dengan nama properti
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048', // max 2MB
            'description' => 'nullable|string',
            'flavors' => 'nullable|string',
            'portions' => 'nullable|string',

            // --- VALIDASI PO DITAMBAHKAN ---
            'is_po' => 'boolean',
            'po_deadline' => 'nullable|date|required_if:is_po,true',
            // ---------------------------------
        ]);

        $product = Product::findOrFail($this->productId);

        // HAPUS ATAU KOMENTARI KODE INI:
// if ($this->image instanceof \Livewire\TemporaryUploadedFile) {

        // GANTI MENJADI SEPERTI INI:
        if ($this->image) {
            // Hapus gambar lama jika ada
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                Storage::disk('public')->delete($this->old_image);
            }
            // Simpan gambar baru
            $imagePath = $this->image->store('products', 'public');
        } else {
            // Jika tidak ada gambar yang diupload, gunakan gambar lama
            $imagePath = $this->old_image;
        }

        // Normalisasi nilai discount sebelum disimpan
        $discount = $this->discount === '' ? null : $this->discount;

        // Proses flavors & portions
        $flavorsArray = null;
        if (!empty($this->flavors)) {
            $flavorsArray = array_map('trim', explode(',', $this->flavors));
            $flavorsArray = array_filter($flavorsArray);
        }

        $portionsArray = null;
        if (!empty($this->portions)) {
            $portionsArray = array_map('trim', explode(',', $this->portions));
            $portionsArray = array_filter($portionsArray);
        }

        $product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->categoryId, // <-- Disesuaikan
            'price' => $this->price,
            'stock' => $this->stock,
            'discount' => $discount, // sudah aman
            'image' => $imagePath,
            'description' => $this->description,
            'flavors' => empty($flavorsArray) ? null : array_values($flavorsArray),
            'portions' => empty($portionsArray) ? null : array_values($portionsArray),

            // --- DATA PO DITAMBAHKAN SAAT UPDATE ---
            'is_po' => $this->is_po,
            'po_deadline' => $this->is_po ? $this->po_deadline : null,
            // ----------------------------------------
        ]);


        // Tutup modal dan beri notifikasi
        $this->dispatch('hideEditModal');
        $this->dispatch('productUpdated');
        $this->dispatch('notify', ['message' => 'Produk berhasil diperbarui.']);

        // Reset field (tambahkan field PO)
        $this->reset(['productId', 'name', 'slug', 'price', 'categoryId', 'discount', 'stock', 'image', 'old_image', 'description', 'flavors', 'portions', 'is_po', 'po_deadline']);
    }
}

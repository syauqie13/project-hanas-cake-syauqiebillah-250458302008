<?php

namespace App\Livewire\Shared\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Import ini diperlukan untuk 'store'

class ProductCreate extends Component
{
    use WithFileUploads;

    public $category_id, $name, $slug, $price, $stock, $discount, $image, $description;
    public $flavors = '';
    public $portions = '';

    // Properti PO dari kode saya (ditambahkan)
    public $is_po = false;
    public $po_deadline;

    public $showModal = false;

    protected $listeners = [
        'openCreateModal' => 'showCreateModal',
    ];

    /**
     * Tampilkan modal dan reset form
     */
    public function showCreateModal()
    {
        // Reset form (termasuk properti PO)
        $this->reset(['category_id', 'name', 'slug', 'price', 'stock', 'discount', 'image', 'description', 'flavors', 'portions', 'is_po', 'po_deadline']);
        $this->resetErrorBag(); // Selalu reset error bag
        $this->showModal = true;

        $this->dispatch('show-create-modal');
    }

    /**
     * (Ditambahkan) Fungsi untuk menutup modal dari blade
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Generate slug otomatis saat mengetik nama
     */
    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updatedName()
    {
        $this->generateSlug();
    }

    /**
     * Simpan produk baru
     */
    public function save()
    {
        // PERBAIKAN: Aturan 'unique' dihapus dari sini,
        // karena Anda menanganinya secara manual di bawah.
        $validated = $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255', // 'unique' dihapus
            'slug' => 'required|string', // 'unique' dihapus
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048', // maksimal 2MB
            'description' => 'nullable|string',
            'flavors' => 'nullable|string',
            'portions' => 'nullable|string',

            // Aturan PO ditambahkan
            'is_po' => 'boolean',
            'po_deadline' => 'nullable|date|required_if:is_po,true',
        ]);

        // Simpan gambar jika diupload
        $imagePath = $this->image
            ? $this->image->store('products', 'public')
            : null;

        // Buat slug unik (logika Anda)
        $slug = $validated['slug'];
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Proses flavors & portions (explode by comma and trim whitespace)
        $flavorsArray = null;
        if (!empty($validated['flavors'])) {
            $flavorsArray = array_map('trim', explode(',', $validated['flavors']));
            $flavorsArray = array_filter($flavorsArray); // Remove empty values
        }

        $portionsArray = null;
        if (!empty($validated['portions'])) {
            $portionsArray = array_map('trim', explode(',', $validated['portions']));
            $portionsArray = array_filter($portionsArray); // Remove empty values
        }

        // Simpan ke database (Tambahkan field PO)
        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug, // Gunakan slug unik
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'discount' => $validated['discount'] ?? 0,
            'image' => $imagePath,
            'description' => $this->description,
            'flavors' => empty($flavorsArray) ? null : array_values($flavorsArray),
            'portions' => empty($portionsArray) ? null : array_values($portionsArray),

            // Field PO yang ditambahkan
            'is_po' => $this->is_po,
            'po_deadline' => $this->is_po ? $this->po_deadline : null, // Hanya simpan jika is_po = true
        ]);

        // Tutup modal & refresh
        $this->dispatch('hide-create-modal');
        $this->dispatch('productCreated');
        $this->dispatch('notify', ['message' => 'Produk berhasil ditambahkan!']);

        // Reset form agar bersih
        $this->reset(['category_id', 'name', 'slug', 'price', 'stock', 'discount', 'image', 'description', 'flavors', 'portions', 'is_po', 'po_deadline']);
    }

    public function render()
    {
        return view('livewire.shared.product.product-create', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }
}

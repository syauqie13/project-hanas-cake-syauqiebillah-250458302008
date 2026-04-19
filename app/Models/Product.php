<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nama tabel (opsional kalau sesuai konvensi)
    protected $table = 'products';
    protected $appends = ['image_url'];


    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'discount',
        'image',
        'slug',
        'is_po',
        'po_deadline',
        'po_fulfillment_date',
        'po_quota'
    ];

    protected $casts = [
        'is_po' => 'boolean', // Ubah 0/1 menjadi true/false
        'po_deadline' => 'datetime', // Ubah string "..." menjadi Objek Carbon
        'po_fulfillment_date' => 'datetime', // Ubah string "..." menjadi Objek Carbon
    ];


    /**
     * Relasi: Product → Category (Many to One)
     * Satu produk hanya punya satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor opsional untuk menampilkan harga setelah diskon.
     */
    public function getFinalPriceAttribute()
    {
        return $this->price - ($this->price * $this->discount / 100);
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
        return url('images/default-product.png'); // Gambar placeholder jika kosong
    }
}

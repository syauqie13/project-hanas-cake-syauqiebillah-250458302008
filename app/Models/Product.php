<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nama tabel (opsional kalau sesuai konvensi)
    protected $table = 'products';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'discount',
        'image',
        'slug',
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'unit',
        'stock',
        'unit_price',
        'description',
    ];

    /**
     * Mendapatkan resep (product recipes) yang menggunakan bahan baku ini.
     * Ini akan kita perlukan nanti untuk CRUD ProductRecipe.
     */
    public function recipes()
    {
        // Pastikan nama class model 'ProductRecipe' benar
        return $this->hasMany(ProductRecipe::class, 'inventory_id');
    }
}

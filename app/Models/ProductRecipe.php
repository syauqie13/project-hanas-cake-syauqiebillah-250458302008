<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    use HasFactory;
    protected $table = 'product_recipes';
    protected $fillable = [
        'product_id',
        'inventory_id',
        'quantity_used',
    ];

    protected $casts = [
        'quantity_used' => 'decimal:2',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function totalUsageForProduction(float $qtyProduct): float
    {
        return $this->quantity_used * $qtyProduct;
    }
}

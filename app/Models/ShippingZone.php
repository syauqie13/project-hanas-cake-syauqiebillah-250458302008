<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'requires_confirmation',
    ];

    /**
     * Cast 'requires_confirmation' sebagai boolean
     */
    protected $casts = [
        'requires_confirmation' => 'boolean',
    ];
}

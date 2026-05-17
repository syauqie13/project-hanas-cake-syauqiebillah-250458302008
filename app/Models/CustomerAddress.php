<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'detail_address',
        'latitude',
        'longitude',
        'receiver_name',
        'receiver_phone',
        'is_primary'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

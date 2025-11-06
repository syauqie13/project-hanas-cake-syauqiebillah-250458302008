<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'cashier_id',
        'tanggal',
        'total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'order_type',
        'status',
        'merchant_order_id'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke kasir (user)
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Relasi ke detail order (kalau nanti ada tabel order_items)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }



    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & HELPERS
    |--------------------------------------------------------------------------
    */

    // Contoh helper untuk cek apakah sudah lunas
    public function isPaid(): bool
    {
        return $this->payment_status === 'lunas';
    }

    // Contoh helper untuk format total
    // public function getFormattedTotalAttribute(): string
    // {
    //     return number_format($this->total, 2, ',', '.');
    // }
}

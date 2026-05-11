<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    // Pastikan fillable sesuai dengan kolom di tabel migration
    protected $fillable = [
        'user_id',
        'voucher_id',
        'is_used',
        'used_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
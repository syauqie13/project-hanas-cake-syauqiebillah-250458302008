<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = ['id'];

    public function claimedBy()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')
            ->withPivot('is_used', 'used_at')
            ->withTimestamps();
    }
}

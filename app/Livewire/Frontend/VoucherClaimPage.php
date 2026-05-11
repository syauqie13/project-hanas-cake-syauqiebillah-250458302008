<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Voucher Saya')]
class VoucherClaimPage extends Component
{
    public function claim($voucherId)
    {
        $user = Auth::user();

        if (!$user) {
            return $this->redirectRoute('login', navigate: true);
        }

        // Cek apakah sudah pernah klaim
        if ($user->claimedVouchers()->where('voucher_id', $voucherId)->exists()) {
            $this->dispatch('notify', ['message' => 'Anda sudah mengklaim voucher ini!', 'icon' => 'warning']);
            return;
        }

        $voucher = Voucher::find($voucherId);
        if (!$voucher || !$voucher->is_active) {
            $this->dispatch('notify', ['message' => 'Voucher tidak valid atau sudah tidak aktif.', 'icon' => 'error']);
            return;
        }

        // Simpan klaim
        $user->claimedVouchers()->attach($voucherId, ['is_used' => false]);
        
        $this->dispatch('notify', ['message' => 'Berhasil klaim voucher! Anda bisa memakainya di halaman Checkout.', 'icon' => 'success']);
    }

    public function render()
    {
        $user = Auth::user();
        
        // Ambil ID voucher yang sudah diklaim
        $claimedIds = $user ? $user->claimedVouchers()->pluck('vouchers.id')->toArray() : [];
        $usedIds = $user ? $user->claimedVouchers()->wherePivot('is_used', true)->pluck('vouchers.id')->toArray() : [];

        // Ambil semua voucher aktif
        $vouchers = Voucher::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            })
            ->latest()
            ->get();

        return view('livewire.frontend.voucher-claim-page', [
            'vouchers' => $vouchers,
            'claimedIds' => $claimedIds,
            'usedIds' => $usedIds
        ]);
    }
}

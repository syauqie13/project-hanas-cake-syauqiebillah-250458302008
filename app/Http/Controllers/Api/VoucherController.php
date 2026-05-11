<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\UserVoucher; // Pastikan Model UserVoucher sudah dibuat

class VoucherController extends Controller
{
    public function apply(Request $request)
    {
        // 1. Validasi input dari Mobile App
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $user = auth('sanctum')->user();
        $subtotal = $request->subtotal;

        // 2. Cari Voucher yang aktif
        $voucher = Voucher::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan atau tidak aktif.'
            ], 404);
        }

        // 3. Cek Masa Berlaku
        if ($voucher->valid_until && $voucher->valid_until < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kedaluwarsa.'
            ], 400);
        }

        // 4. Cek Minimal Belanja
        if ($voucher->min_purchase && $subtotal < $voucher->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja untuk voucher ini adalah Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ], 400);
        }

        // 5. Cek Apakah User Sudah Pernah Menggunakan Voucher Ini
        // Kita cek di tabel user_vouchers apakah is_used = true
        $isUsed = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->where('is_used', true)
            ->exists();

        if ($isUsed) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah pernah menggunakan voucher ini.'
            ], 400);
        }

        // 6. Hitung Besaran Diskon
        $discountAmount = 0;
        if ($voucher->type == 'percentage') {
            $discountAmount = ($subtotal * $voucher->value) / 100;
            // Batasi dengan max_discount jika ada
            if ($voucher->max_discount && $discountAmount > $voucher->max_discount) {
                $discountAmount = $voucher->max_discount;
            }
        } else {
            // Nominal
            $discountAmount = $voucher->value;
        }

        // 7. Hitung Total Akhir
        $finalTotal = $subtotal - $discountAmount;
        if ($finalTotal < 0) {
            $finalTotal = 0;
        }

        // 8. Kembalikan Response Sukses ke Mobile
        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diaplikasikan!',
            'data' => [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'discount_amount' => $discountAmount,
                'subtotal' => $subtotal,
                'final_total' => $finalTotal
            ]
        ], 200);
    }
}
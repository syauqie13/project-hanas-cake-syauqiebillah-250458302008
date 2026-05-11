<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    // 1. API untuk Mengatur / Ubah PIN Pembayaran
    public function setPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6',
            'password' => 'required|string' // Butuh password akun untuk keamanan
        ]);

        $user = auth('sanctum')->user();

        // Verifikasi password asli sebelum membiarkan ganti PIN
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password salah.'], 401);
        }

        // Enkripsi dan simpan PIN
        $user->payment_pin = Hash::make($request->pin);
        $user->save();

        return response()->json(['success' => true, 'message' => 'PIN berhasil disimpan.']);
    }

    // 2. API untuk Memvalidasi PIN sebelum Checkout Mobile
    public function verify(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6'
        ]);

        $user = auth('sanctum')->user();

        // Cek apakah PIN sudah diset?
        if (empty($user->payment_pin)) {
            return response()->json(['success' => false, 'message' => 'PIN belum diatur', 'require_setup' => true], 403);
        }

        // Cek kebenaran PIN
        if (!Hash::check($request->pin, $user->payment_pin)) {
            return response()->json(['success' => false, 'message' => 'PIN tidak valid'], 401);
        }

        return response()->json(['success' => true, 'message' => 'PIN valid, silakan lanjutkan checkout.']);
    }
}
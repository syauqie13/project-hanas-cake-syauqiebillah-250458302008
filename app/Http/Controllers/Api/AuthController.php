<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * AuthController
 *
 * Mengelola autentikasi dan profil pelanggan via API (Flutter).
 * Menggunakan Laravel Sanctum untuk token-based authentication.
 *
 * Fitur:
 * - Register pelanggan baru
 * - Login dengan validasi role (hanya pelanggan)
 * - Lihat & update profil (termasuk upload avatar)
 * - Ganti password
 * - Logout (revoke token)
 */
class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * POST /api/register
     *
     * Mendaftarkan pelanggan baru dan langsung mengembalikan token
     * agar Flutter bisa langsung masuk tanpa login ulang.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'pelanggan', // Diset otomatis — tidak boleh diisi dari request
            'phone'    => $validated['phone'] ?? null,
        ]);

        $token = $user->createToken('flutter-auth-token')->plainTextToken;

        return $this->successResponse([
            'user'  => $user,
            'token' => $token,
        ], 'Registrasi Berhasil', 201);
    }

    /**
     * POST /api/login
     *
     * Login pelanggan. Menolak akses untuk role admin/karyawan
     * agar tidak bisa masuk ke aplikasi mobile pelanggan.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        // Cek apakah user ada dan password benar
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->unauthorizedResponse('Email atau Password salah');
        }

        // PENTING: Cegah admin/karyawan login via aplikasi Flutter pelanggan
        if ($user->role !== 'pelanggan') {
            return $this->forbiddenResponse('Akses ditolak. Aplikasi ini khusus untuk Pelanggan.');
        }

        $token = $user->createToken('flutter-auth-token')->plainTextToken;

        return $this->successResponse([
            'user'  => $user,
            'token' => $token,
        ], 'Login Berhasil');
    }

    /**
     * GET /api/profile
     *
     * Mengambil data profil pelanggan berdasarkan Bearer Token.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        // Sertakan URL avatar lengkap jika ada
        $userData = $user->toArray();
        $userData['avatar_url'] = $user->avatar
            ? url('storage/' . $user->avatar)
            : null;

        return $this->successResponse($userData, 'Data Profil Berhasil Diambil');
    }

    /**
     * POST /api/profile/update
     *
     * Update profil pelanggan termasuk upload foto avatar.
     * Avatar disimpan di storage/app/public/avatars/.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        // Handle upload avatar jika ada file yang dikirim
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada untuk menghemat storage
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan avatar baru dengan nama unik
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Update semua field yang divalidasi (kecuali file 'avatar' yang sudah dihandle)
        $user->update(collect($validated)->except('avatar')->toArray());

        // Update avatar secara terpisah jika ada
        if (isset($validated['avatar'])) {
            $user->update(['avatar' => $validated['avatar']]);
        }

        // Kembalikan data user terbaru dengan URL avatar
        $userData = $user->fresh()->toArray();
        $userData['avatar_url'] = $user->avatar
            ? url('storage/' . $user->avatar)
            : null;

        return $this->successResponse($userData, 'Profil berhasil diperbarui');
    }

    /**
     * POST /api/change-password
     *
     * Ganti password akun. Memerlukan password lama sebagai verifikasi.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        // Verifikasi password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return $this->unauthorizedResponse('Password lama tidak sesuai');
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return $this->successResponse(null, 'Password berhasil diubah');
    }

    /**
     * POST /api/logout
     *
     * Logout dengan menghapus token Sanctum yang sedang digunakan.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout Berhasil');
    }
}
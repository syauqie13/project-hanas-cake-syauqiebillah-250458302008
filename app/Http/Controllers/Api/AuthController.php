<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Mendaftarkan Pelanggan Baru
     */
    public function register(Request $request)
    {
        // 1. Validasi input dari Flutter
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8', // Boleh ditambahkan 'confirmed' jika Flutter kirim confirm_password
            'phone'     => 'nullable|string|max:20', // Opsional di awal
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', // HARUS DISET OTOMATIS KE PELANGGAN
            'phone'    => $request->phone, // Jika diisi saat daftar
        ]);

        // 3. Langsung buatkan token agar setelah register langsung login di aplikasi
        $token = $user->createToken('flutter-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil',
            'data'    => [
                'user'  => $user,
                'token' => $token
            ]
        ], 201); // 201 Created
    }

    /**
     * Login Pelanggan
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek apakah user ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah'
            ], 401); // 401 Unauthorized
        }

        // 4. CEK ROLE (SANGAT PENTING!)
        // Cegah Admin / Karyawan login lewat aplikasi Flutter pelanggan
        if ($user->role !== 'pelanggan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Aplikasi ini khusus untuk Pelanggan.'
            ], 403); // 403 Forbidden
        }

        // 5. Generate Token Sanctum
        $token = $user->createToken('flutter-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'data'    => [
                'user'  => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * Ambil Data Profil Pelanggan
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Profil Berhasil Diambil',
            'data'    => $request->user() // Mengambil data user berdasarkan Token
        ]);
    }

    /**
     * Update Profil Pelanggan
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validasi input (Email unik kecuali email milik user ini sendiri)
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update data
        $user->update($request->only([
            'name', 'email', 'phone', 'address', 'city', 'postal_code'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user
        ]);
    }

    /**
     * Logout Pelanggan
     */
    public function logout(Request $request)
    {
        // Hapus token yang digunakan untuk mengakses endpoint ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil'
        ]);
    }
}
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PinController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\MidtransWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes — Hana's Cake E-Commerce
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis memiliki prefix /api.
| Autentikasi menggunakan Laravel Sanctum (Bearer Token).
|
| Struktur:
| 1. Route Publik (tanpa token)       → Register, Login, Produk, Kategori, Toko
| 2. Route Protected (wajib token)    → Profile, Checkout, Orders, dll.
| 3. Route Webhook (tanpa token/CSRF) → Midtrans Webhook
|
*/

// ============================================================================
// 🔓 ROUTE PUBLIK — Bisa diakses tanpa Bearer Token
// ============================================================================

// --- Auth ---
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1'); // Maks 5 request per menit (anti-spam)

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // Maks 5 request per menit (anti-brute-force)

// --- Katalog Produk & Kategori ---
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// --- Daftar Toko ---
Route::get('/stores', [StoreController::class, 'index']);


// ============================================================================
// 🔒 ROUTE PROTECTED — Wajib kirim Bearer Token Sanctum
// ============================================================================

Route::middleware('auth:sanctum')->group(function () {

    // --- Auth & Profile ---
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Checkout ---
    Route::post('/checkout', [CheckoutController::class, 'process']);

    // --- Orders ---
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // --- PIN Pembayaran ---
    Route::post('/pin/setup', [PinController::class, 'setPin']);
    Route::post('/pin/verify', [PinController::class, 'verify']);

    // --- Alamat Pelanggan ---
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::patch('/addresses/{id}/primary', [AddressController::class, 'setPrimary']);

    // --- Notifikasi ---
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});


// ============================================================================
// 🌐 WEBHOOK — Tidak memerlukan token (diakses langsung oleh Midtrans)
// ============================================================================

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook');

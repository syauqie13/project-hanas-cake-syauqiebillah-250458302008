<?php

use App\Livewire\Auth\Login;
use Illuminate\Http\Request;
use App\Livewire\Front\Konten;
use App\Livewire\Auth\Register;
use App\Livewire\Frontend\Shop;
use App\Livewire\Frontend\CartPage;
use App\Livewire\Frontend\MyOrders;
use App\Livewire\SearchResultsPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\DashboardAdmin;
use App\Livewire\Frontend\CheckoutPage;
use App\Http\Controllers\StrukController;
use App\Livewire\Karyawan\ProductionList;
use App\Livewire\Karyawan\Pos\PosComponent;
use App\Livewire\Karyawan\DashboardKaryawan;
use App\Livewire\Shared\Product\ProductList;
use App\Livewire\Admin\Karyawan\KaryawanList;
use App\Http\Controllers\ValidationController;
use App\Livewire\Shared\Category\CategoryList;
use App\Livewire\Karyawan\Order\OrderManagement;
use App\Livewire\Frontend\UserProfile\EditProfile;
use App\Livewire\Karyawan\Shipping\ZoneManagement;
use App\Livewire\Shared\Inventories\InventoryList;
use App\Http\Controllers\CustomerPaymentController;
use App\Livewire\Karyawan\Pos\PosManagement;
use App\Livewire\Shared\User\Profil;
use App\Livewire\Shared\User\UpdatePassword;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Livewire\Auth\VerifyEmail;

Route::get('/', Konten::class)->name('front');

Route::get('/auth/start-session', Login::class)->name('login')->middleware('guest');
Route::get('/auth/register', Register::class)->name('register')->middleware('guest');

// 1. HALAMAN NOTICE (Tampilan "Cek Email")
Route::get('/email/verify', VerifyEmail::class)->middleware('auth')->name('verification.notice');

// 2. LOGIKA VERIFIKASI (Saat link di email diklik)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // Redirect setelah sukses verifikasi sesuai role
    if ($request->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('karyawan.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::prefix('admin')->middleware(['auth', 'is.admin','verified'])->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardAdmin::class)->name('dashboard');
    Route::get('/employee', KaryawanList::class)->name('list-karyawan');
    Route::get('/product', ProductList::class)->name('list-product');
    Route::get('/category', CategoryList::class)->name('list-category');
    Route::get('/profil', Profil::class)->name('profile');
    Route::get('/profil/update-password', Profil::class)->name('update.password');
});

Route::get('/search', SearchResultsPage::class)->name('search.results');

Route::prefix('karyawan')->middleware(['auth', 'is.karyawan', 'verified'])->name('karyawan.')->group(function () {
    Route::get('/dashboard', DashboardKaryawan::class)->name('dashboard');
    Route::get('/product', ProductList::class)->name('list-product');
    Route::get('/category', CategoryList::class)->name('list-category');
    Route::get('/pos', PosComponent::class)->name('pos');
    Route::get('/inventory', InventoryList::class)->name('list-inventory');
    Route::get('/validasi/{merchantOrderId}', [ValidationController::class, 'show'])->name('kasir.validasi');
    Route::get('/struk/{order}', [StrukController::class, 'print'])->name('struk.print');
    Route::get('/orders', OrderManagement::class)->name('orders.list');
    Route::get('/pos/management', PosManagement::class)->name('pos.list');
    Route::get('/production-list', ProductionList::class)->name('production-list');
    Route::get('/shipping-zones', ZoneManagement::class)->name('shipping-zones');
    Route::get('/profil', Profil::class)->name('profile');
    Route::get('/update-password', UpdatePassword::class)->name('update.password');
});

Route::get('/ecommerce', Shop::class)->name('ecommerce');
Route::get('/cart', CartPage::class)->name('cart');

Route::prefix('pelanggan')->middleware(['auth', 'is.pelanggan', 'verified'])->name('pelanggan.')->group(function () {
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrders::class)->name('my-orders');
    Route::get('/profile', EditProfile::class)->name('profile');
    Route::get('/pay/{order}', [CustomerPaymentController::class, 'show'])->name('pay');
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('front'));
    })->name('logout');
});






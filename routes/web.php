<?php

use App\Livewire\Auth\Login;
use App\Livewire\Front\Konten;
use App\Livewire\Auth\Register;
use App\Livewire\Frontend\Shop;
use App\Livewire\Frontend\CartPage;
use App\Livewire\Frontend\MyOrders;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\DashboardAdmin;
use App\Livewire\Frontend\CheckoutPage;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Karyawan\Pos\PosComponent;
use App\Livewire\Karyawan\DashboardKaryawan;
use App\Livewire\Shared\Product\ProductList;
use App\Livewire\Admin\Karyawan\KaryawanList;
use App\Livewire\Frontend\Auth\CustomerLogin;
use App\Http\Controllers\ValidationController;
use App\Livewire\Shared\Category\CategoryList;
use App\Http\Controllers\DuitkuCallbackController;
use App\Livewire\Shared\Inventories\InventoryList;
use App\Http\Controllers\MidtransWebhookController;

Route::get('/', Konten::class)->name('front');

Route::get('/auth/start-session', Login::class)->name('login')->middleware('guest');
Route::get('/auth/register', Register::class)->name('register')->middleware('guest');

Route::prefix('admin')->middleware(['auth', 'is.admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardAdmin::class)->name('dashboard');
    Route::get('/employee', KaryawanList::class)->name('list-karyawan');
    Route::get('/product', ProductList::class)->name('list-product');
    Route::get('/category', CategoryList::class)->name('list-category');
});

Route::prefix('karyawan')->middleware(['auth', 'is.karyawan'])->name('karyawan.')->group(function () {
    Route::get('/dashboard', DashboardKaryawan::class)->name('dashboard');
    Route::get('/product', ProductList::class)->name('list-product');
    Route::get('/category', CategoryList::class)->name('list-category');
    Route::get('/pos', PosComponent::class)->name('pos');
    Route::get('/inventory', InventoryList::class)->name('list-inventory');
    Route::get('/validasi/{merchantOrderId}', [ValidationController::class, 'show'])->name('kasir.validasi');
    Route::get('/struk/{order}', [StrukController::class, 'print'])->name('struk.print');
});

Route::get('/ecommerce', Shop::class)->name('ecommerce');
Route::get('/cart', CartPage::class)->name('cart');

Route::prefix('pelanggan')->middleware(['auth', 'is.pelanggan'])->name('pelanggan.')->group(function () {
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrders::class)->name('my-orders');
    Route::post('/logout', [CustomerLogin::class, 'logout'])->name('logout');
});




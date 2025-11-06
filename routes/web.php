<?php

use App\Livewire\Auth\Login;
use App\Livewire\Front\Konten;
use App\Livewire\Auth\Register;
use App\Livewire\Shared\Inventories\InventoryList;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\DashboardAdmin;
use App\Http\Controllers\PaymentController;
use App\Livewire\Karyawan\Pos\PosComponent;
use App\Livewire\Karyawan\DashboardKaryawan;
use App\Livewire\Shared\Product\ProductList;
use App\Livewire\Admin\Karyawan\KaryawanList;
use App\Livewire\Shared\Category\CategoryList;
use App\Http\Controllers\DuitkuCallbackController;
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
});

Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');


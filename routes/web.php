<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ShippingOptionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('storefront.home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/orders', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('products.orders.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('admin.login.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/shipping-options', [ShippingOptionController::class, 'index'])->name('shipping.index');
    Route::post('/shipping-options', [ShippingOptionController::class, 'store'])->name('shipping.store');
    Route::put('/shipping-options/{shippingOption}', [ShippingOptionController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping-options/{shippingOption}', [ShippingOptionController::class, 'destroy'])->name('shipping.destroy');
});

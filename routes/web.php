<?php

use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

// Rute untuk menangani permintaan POST untuk logout
// Middleware 'auth' memastikan hanya pengguna yang login yang bisa mengaksesnya
Route::post('/login', [CustomAuthController::class, 'login'])->name('login');

// (Opsional) Rute untuk logout via AJAX
Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

Route::post('/register', [CustomAuthController::class, 'register'])->name('register');

// (Opsional) Rute untuk mengecek status autentikasi via AJAX
Route::get('/user-status', [CustomAuthController::class, 'checkAuth'])->name('user.status');

// ... (routes lainnya) ..

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::post('/cart/add',    [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/orders', [OrderController::class, 'store'])->name('order.store'); // Untuk submit order
// routes/web.php
Route::get('/promo/check', [PromoController::class, 'check']);
    Route::get('/payment-proof/{orderId}', [PaymentProofController::class, 'create'])->name('payment-proof.create');
Route::post('/payment-proof', [PaymentProofController::class, 'store'])->name('payment-proof.store');
});

// Tambahan routes di web.php
// Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
// Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
// Route::post('/orders', [OrderController::class, 'store'])->name('order.store'); // Untuk submit order

Route::get('/auth/google', [CustomAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [CustomAuthController::class, 'handleGoogleCallback']);


Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

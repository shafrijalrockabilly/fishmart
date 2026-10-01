<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/produk', [ProductController::class, 'index']);
Route::get('/produk/{slug}', [ProductController::class, 'show']);

Route::get('/keranjang', [CartController::class, 'index']);
Route::post('/keranjang', [CartController::class, 'store']);
Route::patch('/keranjang/{cartItem}', [CartController::class, 'update']);
Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy']);
Route::post('/checkout', [CartController::class, 'checkout']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
});

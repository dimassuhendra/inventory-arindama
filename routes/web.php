<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\StockExitController;
use App\Http\Controllers\ProductLoanController;
use App\Http\Controllers\CartRequestController;
// use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity-logs', [ActivityLogsController::class, 'index'])->name('activity-log.index');
    Route::delete('/activity-logs/{id}', [ActivityLogsController::class, 'destroy'])->name('activity-log.destroy');
    Route::get('/stock-in', [StockEntryController::class, 'index'])->name('stock-in.index');
    Route::post('/stock-in', [StockEntryController::class, 'store'])->name('stock-in.store');
    Route::put('/stock-in/{id}', [StockEntryController::class, 'update'])->name('stock-in.update');
    Route::delete('/stock-in/{id}', [StockEntryController::class, 'destroy'])->name('stock-in.destroy');
    Route::get('/stock-out', [StockExitController::class, 'index'])->name('stock-out.index');
    Route::post('/stock-out', [StockExitController::class, 'store'])->name('stock-out.store');
    Route::delete('/stock-out/{id}', [StockExitController::class, 'destroy'])->name('stock-out.destroy');
    Route::get('/loans', [ProductLoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [ProductLoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/{id}/return', [ProductLoanController::class, 'returnItem'])->name('loans.return');
    Route::get('/cart-request', [CartRequestController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartRequestController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/submit/{id}', [CartRequestController::class, 'submitRequest'])->name('cart.submit');
    Route::post('/cart/approve/{id}', [CartRequestController::class, 'approve'])->name('cart.approve');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    // Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
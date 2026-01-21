<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\StockExitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stock-in', [StockEntryController::class, 'index'])->name('stock-in.index');
    Route::post('/stock-in', [StockEntryController::class, 'store'])->name('stock-in.store');
    Route::put('/stock-in/{id}', [StockEntryController::class, 'update'])->name('stock-in.update');
    Route::delete('/stock-in/{id}', [StockEntryController::class, 'destroy'])->name('stock-in.destroy');
    Route::get('/stock-out', [StockExitController::class, 'index'])->name('stock-out.index');
    Route::post('/stock-out', [StockExitController::class, 'store'])->name('stock-out.store');
    Route::delete('/stock-out/{id}', [StockExitController::class, 'destroy'])->name('stock-out.destroy');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\kasirController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::get('/logout', [AdminAuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('role:owner')->group(function () {
    Route::get('/owner', [OwnerController::class, 'dashboard']);
    
    // User Management (Owner Only)
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/create', [UserController::class, 'create']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('role:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
});

/*
|--------------------------------------------------------------------------
| SHARED ROUTES (OWNER + ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware('role:owner,admin')->group(function () {
    // Product Management
    Route::get('/admin/products', [ProductController::class, 'index']);
    Route::get('/admin/products/create', [ProductController::class, 'create']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/admin/products/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
    
    // Stock History
    Route::get('/admin/products/stock/history', [ProductController::class, 'stockHistory']);
    Route::get('/admin/products/{id}/stock-history', [ProductController::class, 'productStockHistory']);
    
    // Transaction Management (Delete)
    Route::post('/transaction/{id}/cancel', [ReportController::class, 'cancelTransaction']);
    Route::post('/transaction/{id}/cancel', [ReportController::class, 'cancelTransaction'])
    ->name('transaction.cancel');
    // Reporting Routes
    Route::get('/reports/kasir-history', [ReportController::class, 'kasirHistory']);
    Route::get('/reports/kasir-performance', [ReportController::class, 'kasirPerformance']);
    Route::get('/reports/kasir-detail/{kasirName}', [ReportController::class, 'kasirDetail']);
});

/*
|--------------------------------------------------------------------------
| KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('role:kasir')->group(function () {
    Route::get('/kasir', [kasirController::class, 'index']);
    Route::post('/kasir/cart/add/{id}', [kasirController::class, 'addToCart']);
    Route::post('/kasir/cart/min/{id}', [kasirController::class, 'decreaseQty']);
    Route::post('/kasir/cart/remove/{id}', [kasirController::class, 'removeItem']);
    Route::post('/kasir/cart/clear', [kasirController::class, 'clearCart']);
    Route::post('/kasir/checkout', [kasirController::class, 'checkout']);
});


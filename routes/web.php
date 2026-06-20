<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\kasirController;
use App\Http\Controllers\OwnerController;
/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::get('/logout', [AdminAuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| DASHBOARD ROLE
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner',
    [OwnerController::class, 'dashboard']
)->middleware('role:owner');

Route::get('/admin', fn () => view('admin.dashboard'))
    ->middleware('role:admin');

Route::get('/kasir', [kasirController::class, 'index'])
    ->middleware('role:kasir');

/*
|--------------------------------------------------------------------------
| OWNER USER MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::middleware('role:owner')->group(function () {

    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/create', [UserController::class, 'create']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    Route::post('/admin/users/{id}', [UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| PRODUCT (OWNER + ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('role:owner,admin')->group(function () {

    Route::get('/admin/products', [ProductController::class, 'index']);
    Route::get('/admin/products/create', [ProductController::class, 'create']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/admin/products/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| KASIR TRANSACTION
|--------------------------------------------------------------------------
*/

Route::middleware(['role:kasir'])->group(function () {

    Route::get('/kasir', [kasirController::class, 'index']);

    Route::post('/kasir/cart/add/{id}', [kasirController::class, 'addToCart']);
    Route::post('/kasir/cart/min/{id}', [kasirController::class, 'decreaseQty']);
    Route::post('/kasir/cart/remove/{id}', [kasirController::class, 'removeItem']);
    Route::post('/kasir/cart/clear', [kasirController::class, 'clearCart']);

    Route::post('/kasir/checkout', [kasirController::class, 'checkout']);
});



use App\Http\Controllers\AdminController;

Route::middleware('role:admin')->group(function () {

    Route::get(
        '/admin',
        [AdminController::class, 'dashboard']
    );
    Route::post(
        '/admin/transactions/delete/{id}',
        [AdminController::class, 'deleteTransaction']
    );
});
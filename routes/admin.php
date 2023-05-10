<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ManageUsersController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    // User
    Route::get('/users', [ManageUsersController::class, 'index'])->name('users.index');
    Route::get('/users/paginated', [ManageUsersController::class, 'getPaginated'])->name('users.paginated');
    Route::post('/users/store', [ManageUsersController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}', [ManageUsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [ManageUsersController::class, 'destroy'])->name('users.destroy');
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/paginated', [ProductController::class, 'getPaginated'])->name('products.paginated');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/image-upload', [ProductController::class, 'imageUpload'])->name('products.imageUpload');
    Route::delete('/products/image/{image}', [ProductController::class, 'imageDelete'])
        ->where('image', '[\w-]+\.(jpg|jpeg|png)')
        ->name('products.imageDelete');
});

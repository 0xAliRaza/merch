<?php

use App\Http\Controllers\HomeController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\CartsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [HomeController::class, 'index'])->name('home');



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/products/{product}', [HomeController::class, 'renderProduct'])->name('product');

    // Cart
    Route::get('/cart', [CartsController::class, 'index'])->name('carts.index');
    Route::post('/cart/add', [CartsController::class, 'addToCart'])->name('carts.addToCart');
    // Note: Route key name is defined as product_id in Cart model for route model binding of cart param
    Route::patch('/cart/{cart}/edit', [CartsController::class, 'editCartItem'])->name('carts.edit');
    Route::delete('/cart/{cart}/delete', [CartsController::class, 'deleteCartItem'])->name('carts.delete');
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

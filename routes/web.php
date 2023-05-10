<?php

use App\Http\Controllers\HomeController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManageUsersController;

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

// Route::get('/', function () {
//     return redirect('/users');
//     return Inertia::render('Home', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::get('/product/{id}', function () {
//     return 'working';
// });


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
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
    Route::get('/products/{product}', [HomeController::class, 'renderProduct'])->name('product');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/image-upload', [ProductController::class, 'imageUpload'])->name('products.imageUpload');
    Route::delete('/products/image/{image}', [ProductController::class, 'imageDelete'])
        ->where('image', '[\w-]+\.(jpg|jpeg|png)')
        ->name('products.imageDelete');
    // Cart
    Route::post('/products/cart/add', [ProductController::class, 'addToCart'])->name('products.addToCart');
    Route::get('/products/cart/count', [ProductController::class, 'getCartCount'])->name('products.getCartCount');
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

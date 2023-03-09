<?php

use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return redirect('/users');
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// Route::get('/youtube', function () {
//     return Inertia::render('Youtube');
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/users', [ManageUsersController::class, 'index'])->middleware(['auth', 'verified'])->name('users.index');
Route::get('/users/paginated', [ManageUsersController::class, 'getPaginated'])->middleware(['auth', 'verified'])->name('users.paginated');
Route::post('/users/store', [ManageUsersController::class, 'store'])->middleware(['auth', 'verified'])->name('users.store');
Route::patch('/users/{user}', [ManageUsersController::class, 'update'])->middleware(['auth', 'verified'])->name('users.update');
Route::delete('/users/{user}', [ManageUsersController::class, 'destroy'])->middleware(['auth', 'verified'])->name('users.destroy');
// Products
Route::get('/products', [ProductController::class, 'index'])->middleware(['auth', 'verified'])->name('products.index');
Route::get('/products/paginated', [ProductController::class, 'getPaginated'])->middleware(['auth', 'verified'])->name('products.paginated');
Route::get('/products/create', [ProductController::class, 'create'])->middleware(['auth', 'verified'])->name('products.create');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware(['auth', 'verified'])->name('products.edit');
Route::patch('/products/{product}', [ProductController::class, 'update'])->middleware(['auth', 'verified'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware(['auth', 'verified'])->name('products.destroy');
Route::post('/products/store', [ProductController::class, 'store'])->middleware(['auth', 'verified'])->name('products.store');
Route::post('/products/image-upload', [ProductController::class, 'imageUpload'])->middleware(['auth', 'verified'])->name('products.imageUpload');
Route::delete('/products/image/{image}', [ProductController::class, 'imageDelete'])->middleware(['auth', 'verified'])
    ->where('image', '[\w-]+\.(jpg|jpeg|png)')
    ->name('products.imageDelete');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;

// Ruta principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de autenticación
Auth::routes();

// Rutas públicas de productos
Route::group(['prefix' => 'productos'], function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
});

// Rutas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    // Rutas de servicios
    Route::group(['prefix' => 'servicios'], function () {
        Route::get('/', [ServiceRequestController::class, 'index'])->name('services.index');
        Route::get('/crear', [ServiceRequestController::class, 'create'])->name('services.create');
        Route::post('/', [ServiceRequestController::class, 'store'])->name('services.store');
    });
});

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');


// Rutas de administrador
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Rutas protegidas de administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Productos
    Route::resource('products', ProductController::class);

    // // Servicios
    // Route::get('/services', [ServiceRequestController::class, 'adminIndex'])->name('services.index');
    // Route::patch('/services/{service}/status', [ServiceRequestController::class, 'updateStatus'])
    //      ->name('services.update-status');

   // Rutas completas para servicios
   Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceRequestController::class, 'adminIndex'])->name('index');
    Route::get('/{service}', [ServiceRequestController::class, 'show'])->name('show');
    Route::patch('/{service}/status', [ServiceRequestController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{service}', [ServiceRequestController::class, 'destroy'])->name('destroy');
});



});


// Agregar estas rutas dentro del grupo de rutas autenticadas
Route::middleware(['auth'])->group(function () {
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::patch('/update', [CartController::class, 'update'])->name('update');
        Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });
});

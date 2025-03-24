<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\QRController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', function () {
            redirect()->route('dashboard');
        });
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('clients')->group(function () {
            Route::get('clients', [ClientsController::class, 'index'])->name('admin.clients');
            Route::get('new-client', [ClientsController::class, 'newClient'])->name('admin.new.client');
            Route::get('edit-clients/{id}', [ClientsController::class, 'editClient'])->name('admin.edit.client');

            Route::post('store', [ClientsController::class, 'ClientStore'])->name('admin.client.store');
            Route::put('update/${id}', [ClientsController::class, 'ClientUpdate'])->name('admin.client.update');
            Route::delete('delete/{id}', [ClientsController::class, 'ClientDelete'])->name('admin.client.delete');
        });

        Route::prefix('products')->group(function () {
            Route::get('products', [ProductsController::class, 'index'])->name('admin.products');
            Route::get('new-product', [ProductsController::class, 'newProduct'])->name('admin.new.product');
            Route::get('edit-product/{id}', [ProductsController::class, 'editProduct'])->name('admin.edit.product');

            Route::post('store', [ProductsController::class, 'ProductStore'])->name('admin.product.store');
            Route::put('update/${id}', [ProductsController::class, 'ProductUpdate'])->name('admin.product.update');
        });

        Route::get('qrs', [QRController::class, 'index'])->name('admin.qrs');
        Route::get('contactos', [ContactController::class, 'index'])->name('admin.contactos');
    });
});

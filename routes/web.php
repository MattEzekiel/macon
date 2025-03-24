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

        Route::group(['clients'], function () {
            Route::get('clients', [ClientsController::class, 'index'])->name('admin.clients');
            Route::get('new-clients', [ClientsController::class, 'newClient'])->name('admin.new.client');
            Route::get('edit-clients/{id}', [ClientsController::class, 'editClient'])->name('admin.edit.client');
            Route::post('store', [ClientsController::class, 'ClientStore'])->name('admin.client.store');
            Route::put('update/${id}', [ClientsController::class, 'ClientUpdate'])->name('admin.client.update');
            Route::delete('delete/{id}', [ClientsController::class, 'ClientDelete'])->name('admin.client.delete');
        });

        Route::get('products', [ProductsController::class, 'index'])->name('admin.products');
        Route::get('qrs', [QRController::class, 'index'])->name('admin.qrs');
        Route::get('contactos', [ContactController::class, 'index'])->name('admin.contactos');
    });
});

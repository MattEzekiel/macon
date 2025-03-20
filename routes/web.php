<?php

use App\Http\Controllers\AdminController;
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
            Route::get('clients', [AdminController::class, 'clients'])->name('admin.clients');
            Route::post('new-client', [AdminController::class, 'ClientStore'])->name('admin.client.store');
        });

        Route::get('qrs', [AdminController::class, 'QR'])->name('admin.qrs');
        Route::get('contactos', [AdminController::class, 'contactos'])->name('admin.contactos');
    });
});

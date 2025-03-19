<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'loginForm'])->name('loginForm');
    Route::post('login', [AdminController::class, 'login'])->name('login');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', function () {
            redirect()->route('dashboard');
        });
       Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
})->name('admin.');

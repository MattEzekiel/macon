<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {

    Route::middleware(['auth', 'admin'])->group(function () {
       Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
})->name('admin.');

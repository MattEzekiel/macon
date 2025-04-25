<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('links');
})->name('home');

Route::get('/search/{payload}', [QRController::class, 'DisplayData'])->name('public_qr');
Route::get('/files/{id}/increment-visits', [FilesController::class, 'incrementVisits'])->name('files.increment-visits');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'loginForm'])->name('admin.login.form');
    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgot-password.form');
    Route::get('restore-password/{token}', [AdminController::class, 'restorePasswordForm'])->name('admin.restore.password.token');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('restore-password', [AdminController::class, 'restorePassword'])->name('admin.restore.password');
    Route::post('reset-password', [AdminController::class, 'resetPassword'])->name('admin.reset.password');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', function () {
            redirect()->route('dashboard');
        });
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('change-language', [AdminController::class, 'changeLanguage'])->name('admin.change.language');

        Route::prefix('clients')->group(function () {
            Route::get('/', [ClientsController::class, 'index'])->name('admin.clients');
            Route::get('new-client', [ClientsController::class, 'newClient'])->name('admin.new.client');
            Route::get('edit-clients/{id}', [ClientsController::class, 'editClient'])->name('admin.edit.client');

            Route::post('store', [ClientsController::class, 'ClientStore'])->name('admin.client.store');
            Route::put('update/{id}', [ClientsController::class, 'ClientUpdate'])->name('admin.client.update');
            Route::delete('delete/{id}', [ClientsController::class, 'ClientDelete'])->name('admin.client.delete');
            Route::patch('restore/{id}', [ClientsController::class, 'ClientRestore'])->name('admin.client.restore');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->name('admin.products');
            Route::get('new-product', [ProductsController::class, 'newProduct'])->name('admin.new.product');
            Route::get('edit-product/{id}', [ProductsController::class, 'editProduct'])->name('admin.edit.product');

            Route::post('store', [ProductsController::class, 'ProductStore'])->name('admin.product.store');
            Route::put('update/{id}', [ProductsController::class, 'ProductUpdate'])->name('admin.product.update');
            Route::delete('delete/{id}', [ProductsController::class, 'ProductDelete'])->name('admin.product.delete');
            Route::patch('restore/{id}', [ProductsController::class, 'ProductRestore'])->name('admin.product.restore');
        });

        Route::prefix('files')->group(function () {
            Route::get('/', [FilesController::class, 'index'])->name('admin.files');
            Route::get('new-file/{id}', [FilesController::class, 'newFiles'])->name('admin.new.files');
            Route::get('edit-file/{id}', [FilesController::class, 'editFiles'])->name('admin.edit.files');
            Route::get('name-file/{id}', [FilesController::class, 'nameFiles'])->name('admin.name.files');

            Route::post('store', [FilesController::class, 'FileStore'])->name('admin.file.store');
            Route::put('update', [FilesController::class, 'FileUpdate'])->name('admin.file.update');
            Route::put('rename', [FilesController::class, 'FileRename'])->name('admin.file.name');
            Route::delete('delete/{id}', [FilesController::class, 'FileDelete'])->name('admin.file.delete');
        });

        Route::prefix('qr')->group(function () {
            Route::get('/', [QRController::class, 'index'])->name('admin.qrs');
            Route::get('new-qr/{id?}', [QRController::class, 'newQR'])->name('admin.new.qr');

            Route::post('store', [QRController::class, 'QRStore'])->name('admin.qr.store');
        });

        Route::prefix('users')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('admin.users');
            Route::get('new-user', [UserController::class, 'newUser'])->name('admin.new.user');
            Route::get('edit-user/{id}', [UserController::class, 'editUser'])->name('admin.edit.user');

            Route::post('store', [UserController::class, 'UserStore'])->name('admin.user.store');
            Route::put('update/{id}', [UserController::class, 'UserUpdate'])->name('admin.user.update');
            Route::delete('delete/{id}', [UserController::class, 'UserDelete'])->name('admin.user.delete');
        });

        Route::get('contactos', [ContactController::class, 'index'])->name('admin.contactos');
    });
});

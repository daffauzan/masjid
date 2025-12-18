<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminKonten as AdminKontenController;
use App\Http\Controllers\Admin\AdminZakat as AdminZakatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminController;

// User
Route::get('/', function () {
    return view('user.index');
});

// admin
Route::prefix('admin')->name('admin.')->group(function () {

    // DASHBOARD
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // KONTEN
    Route::prefix('konten')->name('konten.')->group(function () {
        Route::get('/', [AdminKontenController::class, 'index'])->name('index');
        Route::get('/create', [AdminKontenController::class, 'create'])->name('create');
        Route::post('/', [AdminKontenController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminKontenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminKontenController::class, 'update'])->name('update');
    });

    // ZAKAT
    Route::prefix('zakat')->name('zakat.')->group(function () {
        Route::get('/', [AdminZakatController::class, 'index'])->name('index');
        Route::get('/create', [AdminZakatController::class, 'create'])->name('create');
        Route::post('/', [AdminZakatController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminZakatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminZakatController::class, 'update'])->name('update');
    });
});
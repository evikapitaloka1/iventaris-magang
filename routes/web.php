<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

// 1. Arahkan pengunjung awal langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Semua rute di dalam grup ini wajib login terlebih dahulu
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Master Data Barang (Otomatis membuat index, create, store, show, edit, update, destroy)
    Route::resource('products', ProductController::class);

    // Rute Modul Peminjaman Barang
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');

    // Rute Profile (Bawaan instalasi Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. Memuat rute autentikasi bawaan Breeze (Login, Register, Logout, dll)
require __DIR__.'/auth.php';
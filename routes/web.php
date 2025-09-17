<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


// Route dummy dashboard supaya Breeze/Fortify tidak error
Route::get('/dashboard', function () {
    return redirect('/redirect-by-role');
})->middleware('auth')->name('dashboard');

// Root, redirect ke login jika belum auth
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect('/redirect-by-role');
});

/// ================== AUTH LOGIN / REGISTER / LOGOUT ==================

// LOGIN
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// REGISTER
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// LOGOUT
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/redirect-by-role', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    }

    // Default untuk pengguna biasa
    return redirect()->route('pengaduan.index');
})->middleware('auth');

// ================== ADMIN ==================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola petugas
    Route::get('/petugas', [AdminController::class, 'listPetugas'])->name('petugas.index');
    Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('petugas.store');

    // kalau mau: Route::resource('items', ItemController::class);
    // kalau mau: Route::resource('lokasi', LokasiController::class);
});

// ================== PETUGAS ==================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::put('/pengaduan/{id}/status', [PetugasController::class, 'updateStatus'])->name('updateStatus');
});

// ================== PENGGUNA ==================
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::resource('pengaduan', PengaduanController::class)->only(['index', 'create', 'store']);
});

// ================== PROFILE ==================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kalau masih ada route auth bawaan Breeze, bisa dihapus/disable
// require __DIR__.'/auth.php';

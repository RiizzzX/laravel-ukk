<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Default & Auth Routes
|--------------------------------------------------------------------------
*/

// Redirect default
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect('/redirect-by-role');
});

// Dummy dashboard supaya Breeze/Fortify tidak error
Route::get('/dashboard', function () {
    return redirect('/redirect-by-role');
})->middleware('auth')->name('dashboard');

// ================== AUTH ==================
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

// Redirect by role
Route::get('/redirect-by-role', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    }
    return redirect()->route('pengaduan.index'); // default user
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Kelola Petugas
        Route::get('/petugas', [AdminController::class, 'listPetugas'])->name('petugas.index');
        Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('petugas.store');

        // Kelola Items
        Route::resource('items', ItemController::class);

        // Kelola Lokasi
        Route::resource('lokasi', LokasiController::class);

        // Kelola Pengaduan (list semua pengaduan)
        Route::get('/pengaduan', [AdminController::class, 'listPengaduan'])->name('pengaduan.index');

        Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Hapus pengaduan
    Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola pengaduan
    Route::get('/pengaduan', [AdminController::class, 'listPengaduan'])->name('pengaduan.index');
    Route::delete('/pengaduan/{id}', [AdminController::class, 'destroyPengaduan'])->name('pengaduan.destroy');
});

});

    });

Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::resource('pengaduan', \App\Http\Controllers\PengaduanController::class)->only(['index','create','store']);
});
Route::post('/admin/pengaduan/{id}/status', [AdminController::class, 'updateStatusPengaduan'])
    ->name('admin.pengaduan.updateStatus');

/*
|--------------------------------------------------------------------------
| Petugas Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::put('/pengaduan/{id}/status', [PetugasController::class, 'updateStatus'])->name('updateStatus');
    });


/*
|--------------------------------------------------------------------------
| Pengguna Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pengguna'])
    ->group(function () {
        Route::resource('pengaduan', PengaduanController::class)->only(['index', 'create', 'store']);
    });

// routes/web.php
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::resource('pengaduan', PengaduanController::class)
        ->only(['index','create','store','edit','update','destroy']);
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

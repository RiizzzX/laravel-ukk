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

        // Kelola Users
        Route::get('/users', [AdminController::class, 'listUsers'])->name('users.index');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Kelola Petugas
        Route::get('/petugas', [AdminController::class, 'listPetugas'])->name('petugas.index');
        Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('petugas.store');
        Route::put('/petugas/{id}', [AdminController::class, 'updatePetugas'])->name('petugas.update');
        Route::delete('/petugas/{id}', [AdminController::class, 'destroyPetugas'])->name('petugas.destroy');

        // Kelola Items
        Route::resource('items', ItemController::class);

        // Kelola Lokasi
        Route::resource('lokasi', LokasiController::class);

        // Kelola Pengaduan (sudah ada prefix admin. dari group)
        Route::get('/pengaduan', [AdminController::class, 'listPengaduan'])->name('pengaduan.index');
        Route::get('/pengaduan/riwayat', [AdminController::class, 'riwayatPengaduan'])->name('pengaduan.riwayat');
        Route::delete('/pengaduan/{id}', [AdminController::class, 'destroyPengaduan'])->name('pengaduan.destroy');
        Route::post('/pengaduan/{id}/status', [AdminController::class, 'updateStatusPengaduan'])->name('pengaduan.updateStatus');
        
        // Generate Laporan
        Route::get('/laporan', [AdminController::class, 'generateLaporan'])->name('laporan');
    });

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
        Route::resource('pengaduan', PengaduanController::class)
            ->only(['index','create','store','edit','update','destroy']);
        
        Route::get('/pengaduan-riwayat', [PengaduanController::class, 'riwayat'])->name('pengaduan.riwayat');
        Route::get('/saran-item', [PengaduanController::class, 'saran'])->name('pengaduan.saran');
        Route::post('/saran-item', [PengaduanController::class, 'storeSaran'])->name('pengaduan.storeSaran');
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
| Base URL: http://localhost/api/
| Authentication: Bearer Token (Laravel Sanctum)
*/

// ============ PUBLIC ROUTES (No Auth Required) ============

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Master Data (Public - untuk dropdown di mobile)
Route::get('/lokasi', [LokasiController::class, 'index']);
Route::get('/lokasi/{id}', [LokasiController::class, 'show']);
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/by-lokasi/{id_lokasi}', [ItemController::class, 'byLokasi']);

// ============ PROTECTED ROUTES (Auth Required) ============

Route::middleware('auth:sanctum')->group(function () {
    
    // Auth User Info & Profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile/update-password', [AuthController::class, 'updatePassword']);
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Pengaduan Management
    Route::prefix('pengaduan')->group(function () {
        Route::get('/', [PengaduanController::class, 'index']); // List semua pengaduan user
        Route::post('/', [PengaduanController::class, 'store']); // Buat pengaduan baru
        
        // Filter by status - HARUS SEBELUM /{id} agar tidak konflik
        Route::get('/status/pending', [PengaduanController::class, 'pending']);
        Route::get('/status/diproses', [PengaduanController::class, 'diproses']);
        Route::get('/status/selesai', [PengaduanController::class, 'selesai']);
        Route::get('/status/ditolak', [PengaduanController::class, 'ditolak']);
        
        // Riwayat (selesai + ditolak) - HARUS SEBELUM /{id}
        Route::get('/riwayat/all', [PengaduanController::class, 'riwayat']);
        
        // Saran item - HARUS SEBELUM /{id}
        Route::post('/saran', [PengaduanController::class, 'saran']);
        
        // Detail, update, delete - TARUH DI AKHIR
        Route::get('/{id}', [PengaduanController::class, 'show']); // Detail pengaduan
        Route::put('/{id}', [PengaduanController::class, 'update']); // Update pengaduan (jika masih pending)
        Route::delete('/{id}', [PengaduanController::class, 'destroy']); // Hapus pengaduan
    });

    // Statistics
    Route::get('/statistics', [PengaduanController::class, 'statistics']);
});


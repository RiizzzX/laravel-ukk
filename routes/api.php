<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengaduanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',                 [AuthController::class, 'me']);
    Route::put('/profile',            [AuthController::class, 'updateProfile']);

    Route::post('/pengaduan',         [PengaduanController::class, 'store']);
    Route::get('/pengaduan/history',  [PengaduanController::class, 'history']);
    // optional: route show single pengaduan
    Route::get('/pengaduan/{id}',     [PengaduanController::class, 'show']);
});

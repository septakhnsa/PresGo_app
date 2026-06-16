<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\JadwalController;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ─── Forgot Password ─────────────────────────────────────────────────────────
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ─── User info & Data (Sanctum) ──────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/jadwal', [JadwalController::class, 'getMyJadwal']);
    Route::post('/presensi/submit', [JadwalController::class, 'submitPresensi']);
});

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard']);
    Route::get('/jadwal',                 [AdminController::class, 'jadwal']);
    Route::get('/presensi/{jadwal_id}',   [AdminController::class, 'presensi']);
});
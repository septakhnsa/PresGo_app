<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\KrsController;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ─── Forgot Password ─────────────────────────────────────────────────────────
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ─── Public ──────────────────────────────────────────────────────────────────
Route::get('/krs/matakuliah', [KrsController::class, 'matakuliah']);

// ─── User (Sanctum Auth) ─────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Jadwal & Presensi
    Route::get('/jadwal/my', [JadwalController::class, 'getMyJadwal']);
    Route::post('/presensi/submit', [JadwalController::class, 'submitPresensi']);

    // Rekap kehadiran user yang login (dipindah dari admin group)
    Route::get('/presensi/rekap', [JadwalController::class, 'getRekapKehadiran']);
    Route::get('/presensi/history', [JadwalController::class, 'getMyHistory']);

    // KRS
    Route::post('/krs', [KrsController::class, 'store']);
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::post('/krs/approve/{id}', [KrsController::class, 'approve']);
    Route::post('/krs/reject/{id}', [KrsController::class, 'reject']);
    Route::get('/krs/pending', [KrsController::class, 'pending']);
    Route::get('/presensi/rekap-admin', [JadwalController::class, 'getRekapKehadiran']);
});
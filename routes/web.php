<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\MahasiswaWebController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ───────────────────────────────────────────────────────────────────────────
// Mahasiswa (Student) Web Pages — mirrors the PresGo mobile Figma design
// ───────────────────────────────────────────────────────────────────────────
Route::get('/', [MahasiswaWebController::class, 'splash'])->name('splash');

Route::get('/masuk', [MahasiswaWebController::class, 'showLoginForm'])->name('mahasiswa.login');
Route::post('/masuk', [MahasiswaWebController::class, 'login'])->name('mahasiswa.login.submit');

Route::get('/daftar', [MahasiswaWebController::class, 'showRegisterForm'])->name('mahasiswa.register');
Route::post('/daftar', [MahasiswaWebController::class, 'register'])->name('mahasiswa.register.submit');

// Lupa Password — TIDAK pakai middleware auth, karena dipakai SAAT BELUM login
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('mahasiswa.forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('mahasiswa.forgot-password.submit');

Route::get('/otp-verification', [AuthController::class, 'showOtp'])->name('mahasiswa.otp');
Route::post('/otp-verification', [AuthController::class, 'verifyOtp'])->name('mahasiswa.otp.verify');
Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('mahasiswa.otp.resend');

Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('mahasiswa.password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('mahasiswa.password.update');

Route::get('/password-verified', [AuthController::class, 'showPasswordVerified'])->name('mahasiswa.password.verified');

Route::middleware(['auth'])->group(function () {
    Route::get('/beranda',             [MahasiswaWebController::class, 'home'])->name('mahasiswa.home');
    Route::get('/dashboard-presensi', [MahasiswaWebController::class, 'dashboardPresensi'])->name('mahasiswa.dashboard-presensi');
    Route::get('/notifikasi',          [MahasiswaWebController::class, 'notifikasi'])->name('mahasiswa.notifikasi');
    Route::get('/profile',             [MahasiswaWebController::class, 'profile'])->name('mahasiswa.profile');
    Route::get('/history',             [MahasiswaWebController::class, 'history'])->name('mahasiswa.history');
    Route::get('/presensi/camera',     [MahasiswaWebController::class, 'camera'])->name('mahasiswa.presensi.camera');
    Route::post('/keluar',             [MahasiswaWebController::class, 'logout'])->name('mahasiswa.logout');
});

// ───────────────────────────────────────────────────────────────────────────
// Admin Web Login (unchanged)
// ───────────────────────────────────────────────────────────────────────────
Route::get('/login', function () {
    return redirect()->route('mahasiswa.login');
})->name('login');

// Auto Login route from Flutter App
Route::get('/admin/auto-login', [AdminWebController::class, 'autoLogin'])->name('admin.auto_login');

// Protected Web Admin Panel Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard',              [AdminWebController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/api',          [AdminWebController::class, 'dashboardApi'])->name('admin.dashboard.api');
    Route::get('/jadwal',                 [AdminWebController::class, 'jadwal'])->name('admin.jadwal');
    Route::get('/mahasiswa',              [AdminWebController::class, 'mahasiswa'])->name('admin.mahasiswa');
    Route::post('/mahasiswa/{id}/verify', [AdminWebController::class, 'verifyMahasiswa'])->name('admin.mahasiswa.verify');
    Route::get('/presensi',               [AdminWebController::class, 'presensi'])->name('admin.presensi');
    Route::post('/logout',                [AdminWebController::class, 'logout'])->name('admin.logout');
});
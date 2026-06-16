<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminWebController;

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

// Welcome / Landing Page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Web Routes
Route::get('/login', [AdminWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminWebController::class, 'loginWeb']);

// Auto Login route from Flutter App
Route::get('/admin/auto-login', [AdminWebController::class, 'autoLogin'])->name('admin.auto_login');

// Protected Web Admin Panel Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/api', [AdminWebController::class, 'dashboardApi'])->name('admin.dashboard.api');
    Route::get('/jadwal', [AdminWebController::class, 'jadwal'])->name('admin.jadwal');
    Route::get('/mahasiswa', [AdminWebController::class, 'mahasiswa'])->name('admin.mahasiswa');
    Route::post('/mahasiswa/{id}/verify', [AdminWebController::class, 'verifyMahasiswa'])->name('admin.mahasiswa.verify');
    Route::get('/presensi', [AdminWebController::class, 'presensi'])->name('admin.presensi');
    Route::post('/logout', [AdminWebController::class, 'logout'])->name('admin.logout');
});

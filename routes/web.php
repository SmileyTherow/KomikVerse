<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;

// ================= PUBLIC =================

// Registration + OTP
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

// Redirect root
Route::get('/', fn () => redirect()->route('comics.index'));

// Comics
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{id}', [ComicController::class, 'show'])->name('comics.show');

// Login / Logout
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');

// ================= AUTH + VERIFIED =================
Route::middleware(['auth', \App\Http\Middleware\EnsureEmailIsVerified::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Borrowings
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/request', [BorrowingController::class, 'requestBorrow'])->name('borrowings.request');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ================= ADMIN =================
Route::prefix('admin')->name('admin.')->middleware(['auth','is.admin'])->group(function () {
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/{id}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{id}/return', [AdminBorrowingController::class, 'processReturn'])->name('borrowings.return');
});

// ================= STATIC =================
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/about', [PageController::class, 'about'])->name('about');

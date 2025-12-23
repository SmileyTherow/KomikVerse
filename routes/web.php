<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\DashboardController;

// Public: registration + OTP
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

// Public comics listing & detail
Route::get('/', function () {
    return redirect()->route('comics.index');
});

Route::get('/comics', [\App\Http\Controllers\ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{id}', [\App\Http\Controllers\ComicController::class, 'show'])->name('comics.show');

// Auth routes assumed exist (login/register). If using Laravel Breeze, the routes already in place.
// Borrowing (user)
Route::middleware(['auth', 'verified.email'])->group(function () {
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/request', [BorrowingController::class, 'requestBorrow'])->name('borrowings.request');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth','is.admin'])->group(function () {
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/{id}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{id}/return', [AdminBorrowingController::class, 'processReturn'])->name('borrowings.return');
});

// Login / Logout routes
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login.attempt');
Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

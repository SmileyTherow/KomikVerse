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
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\StatisticsController as AdminStatisticsController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| Public / Authentication Routes
|--------------------------------------------------------------------------
*/

// Registration + OTP
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

// Login / Logout
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.attempt');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Redirect root -> comics listing
Route::get('/', fn() => redirect()->route('comics.index'));

/*
|--------------------------------------------------------------------------
| Public Comics Routes
|--------------------------------------------------------------------------
*/
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{comic}', [ComicController::class, 'show'])->name('comics.show');

// Like / Unlike (protected by auth middleware)
Route::post('/comics/{comic}/like', [ComicController::class, 'like'])->middleware('auth')->name('comics.like');
Route::delete('/comics/{comic}/like', [ComicController::class, 'unlike'])->middleware('auth')->name('comics.unlike');

/*
|--------------------------------------------------------------------------
| Authenticated + Verified (user) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class, \App\Http\Middleware\EnsureEmailIsVerified::class])->group(function () {
    // Dashboard (user)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Borrowings (user)
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/request', [BorrowingController::class, 'requestBorrow'])->name('borrowings.request');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class, \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Borrowings management
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings/{id}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{id}/return', [AdminBorrowingController::class, 'processReturn'])->name('borrowings.return');
    Route::post('/borrowings/{id}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');

    // Comics management (use explicit routes to keep control)
    Route::get('/comics', [AdminComicController::class, 'index'])->name('comics.index');
    Route::get('/comics/create', [AdminComicController::class, 'create'])->name('comics.create');
    Route::post('/comics', [AdminComicController::class, 'store'])->name('comics.store');
    Route::get('/comics/{comic}/edit', [AdminComicController::class, 'edit'])->name('comics.edit');
    Route::put('/comics/{comic}', [AdminComicController::class, 'update'])->name('comics.update');
    Route::delete('/comics/{comic}', [AdminComicController::class, 'destroy'])->name('comics.destroy');

    // Genres management (new)
    Route::get('/genres', [AdminGenreController::class, 'index'])->name('genres.index');
    Route::get('/genres/create', [AdminGenreController::class, 'create'])->name('genres.create');
    Route::post('/genres', [AdminGenreController::class, 'store'])->name('genres.store');
    Route::get('/genres/{genre}/edit', [AdminGenreController::class, 'edit'])->name('genres.edit');
    Route::put('/genres/{genre}', [AdminGenreController::class, 'update'])->name('genres.update');
    Route::delete('/genres/{genre}', [AdminGenreController::class, 'destroy'])->name('genres.destroy');

    // Users management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // User management (status & messaging) — handled by UserManagementController
    Route::post('/users/{id}/status', [UserManagementController::class, 'updateStatus'])->name('users.updateStatus');
    Route::get('/users/{id}/message', [UserManagementController::class, 'showMessageForm'])->name('users.showMessageForm');
    Route::post('/users/{id}/message', [UserManagementController::class, 'sendMessage'])->name('users.sendMessage');

    // Admin utilities
    Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('statistics');
    Route::get('/activity', [AdminActivityController::class, 'index'])->name('activity');

    // Categories management
    Route::resource('categories', CategoryController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Static Pages
|--------------------------------------------------------------------------
*/
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/about', [PageController::class, 'about'])->name('about');

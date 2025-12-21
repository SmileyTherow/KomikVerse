<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // if using Laravel auth scaffold

// Public: registration + OTP
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

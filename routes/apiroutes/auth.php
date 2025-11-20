<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::post('login', [AuthenticatedSessionController::class, 'login']);

    Route::post('verify-email', [RegisteredUserController::class, 'verify_email']);

    Route::post('resend-verification-email', [RegisteredUserController::class, 'resend_otp']);

    Route::post('forgot-password', [AuthenticatedSessionController::class, 'forgot_password']);
    Route::post('reset-password', [AuthenticatedSessionController::class, 'reset_password']);
    Route::post('resend-otp', [AuthenticatedSessionController::class, 'resend_otp']);

});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

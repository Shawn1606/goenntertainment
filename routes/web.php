<?php

use App\Http\Controllers\Auth\CompleteGoogleRegistrationController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureProfileIsComplete;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function (): void {
    // Login ist nur das Sheet auf der Startseite – GET leitet dorthin (Flash-Meldung bleibt erhalten).
    Route::get('login', function () {
        session()->reflash();

        return redirect()->route('welcome');
    })->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
});

Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('auth/google/complete', [CompleteGoogleRegistrationController::class, 'create'])->name('google.complete');
    Route::post('auth/google/complete', [CompleteGoogleRegistrationController::class, 'store'])->name('google.complete.store');

    Route::middleware(EnsureProfileIsComplete::class)->group(function (): void {
        Route::get('home', [HomeController::class, 'index'])->name('home');
    });
});

<?php

use App\Http\Controllers\AdminPanel\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminPanel\Auth\ConfirmablePasswordController;
use App\Http\Controllers\AdminPanel\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\AdminPanel\Auth\EmailVerificationPromptController;
use App\Http\Controllers\AdminPanel\Auth\NewPasswordController;
use App\Http\Controllers\AdminPanel\Auth\PasswordResetLinkController;
use App\Http\Controllers\AdminPanel\Auth\RegisteredUserController;
use App\Http\Controllers\AdminPanel\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware(['guest', 'checknouserauth'])
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware(['guest', 'checknouserauth']);

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware(['checknouserauth'])
                ->name('adminPanel.login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware(['checknouserauth'])
                ->name('adminPanel.login');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'showLinkRequestForm'])
                ->middleware(['guest', 'checknouserauth'])
                ->name('adminPanel.passwordRest');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'sendResetLinkEmail'])
                ->middleware(['guest', 'checknouserauth'])
                ->name('adminPanel.passwordEmail');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest', 'checknouserauth'])
                ->name('password.request');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware(['guest', 'checknouserauth'])
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('adminPanel.logout');

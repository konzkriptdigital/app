<?php

use App\Http\Controllers\GoogleController;
use App\Livewire\Auth\VerificationCode;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');
/*
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); */
Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('/auth/google/redirect', [GoogleController::class, 'googleOauth'])
    ->name('google.oauth');

Route::get('/auth/google/callback', [GoogleController::class, 'googleCallback'])
    ->name('google.callback');

/* Route::get('/verifiy-code', VerificationCode::class)
    ->name('verify.code'); */


require __DIR__.'/auth.php';

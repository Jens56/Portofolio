<?php

use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;

Route::get('/', [ChirpController::class, 'index']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/chirps', [ChirpController::class, 'store']);
    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit']);
    Route::put('/chirps/{chirp}', [ChirpController::class, 'update']);
    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy']);
});

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register'); 

Route::post('/register', Register::class)
    ->middleware('guest');

Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');

Route::view('/login', 'auth/login')
    ->middleware('guest')
    ->name('login');
Route::post('login', login::class)
    ->middleware('guest');


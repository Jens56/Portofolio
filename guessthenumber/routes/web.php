<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [SettingsController::class, 'randomNumber'])->name('home');
    Route::get('/settings', [SettingsController::class, 'settings']);
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::get('/countdown', [SettingsController::class, 'getCountdown']);
    Route::get('/posts/{id}', [ScoreController::class, 'show'])->name('score.show');
    Route::post('/check-number', [SettingsController::class, 'checkCorrectNumber'])->middleware('auth');
    Route::delete('/highscore/{id}/delete', [ScoreController::class, 'destroy']);
    Route::get('/highscores', [ScoreController::class, 'index'])->name('highscores');
});

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', Register::class)->middleware('guest');

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', Login::class)->middleware('guest');

Route::get('/logout', Logout::class)->middleware('auth')->name('logout');
Route::post('/logout', Logout::class)->middleware('auth');
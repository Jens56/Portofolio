<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;

Route::get('/', function () {
    return view('home');
});

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');
 
Route::post('/register', Register::class)
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/day', [PlanController::class, 'index'])->name('day');
    Route::resource('tasks', PlanController::class);
    Route::get('/important', [PlanController::class, 'index'])->name('important');
    Route::get('/planned', [PlanController::class, 'index'])->name('planned');
    Route::get('/gotSend', [PlanController::class, 'index'])->name('send');
    Route::patch('/important/{task}/toggle-important', [PlanController::class, 'toggleImportant'])
        ->name('important.toggleImportant');
    
});

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');
 
Route::post('/login', Login::class)
    ->middleware('guest');
 
// Logout route
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');
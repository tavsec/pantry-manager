<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PantryItemController;
use App\Http\Controllers\PantryLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('pantry', PantryItemController::class)->parameters([
        'pantry' => 'pantryItem',
    ]);

    Route::get('/pantry-logs', [PantryLogController::class, 'index'])->name('pantry-logs.index');
    Route::post('/pantry/{pantryItem}/log', [PantryLogController::class, 'store'])->name('pantry.log.store');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('locations', LocationController::class)->except(['show']);
});

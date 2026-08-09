<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\EnemyController;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('/forge', 'DataForge')->name('forge');

Route::post('/weapons', [WeaponController::class, 'store']);
Route::post('/enemies', [EnemyController::class, 'store']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';

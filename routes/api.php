<?php

use App\Http\Controllers\BuildController;
use App\Http\Controllers\EnemyController;
use App\Http\Controllers\WeaponController;
use Illuminate\Support\Facades\Route;

Route::get('/weapons', [WeaponController::class, 'index']);
Route::get('/enemies', [EnemyController::class, 'index']);
Route::get('/builds', [BuildController::class, 'index']);
Route::post('/weapons', [WeaponController::class, 'store']);
Route::post('/enemies', [EnemyController::class, 'store']);

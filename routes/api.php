<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\EnemyController;
use App\Http\Controllers\BuildController;

Route::get('/weapons', [WeaponController::class, 'index']);
Route::get('/enemies', [EnemyController::class, 'index']);
Route::get('/builds', [BuildController::class, 'index']);
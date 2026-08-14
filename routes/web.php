<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\AmmoController;
use App\Http\Controllers\ArmorPieceController;
use App\Http\Controllers\EnemyController;
use App\Http\Controllers\WeaponController;
use App\Models\Accessory;
use App\Models\Ammo;
use App\Models\ArmorPiece;
use App\Models\Enemy;
use App\Models\Weapon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'weapons' => Weapon::all(),
        'enemies' => Enemy::all(),
        'armorPieces' => ArmorPiece::all(),
        'accessories' => Accessory::all(),
        'ammos' => Ammo::all(),
    ]);
})->name('home');

Route::inertia('/forge', 'DataForge')->name('forge');
Route::post('/weapons', [WeaponController::class, 'store']);
Route::post('/enemies', [EnemyController::class, 'store']);
Route::post('/armor-pieces', [ArmorPieceController::class, 'store']);
Route::post('/accessories', [AccessoryController::class, 'store']);
Route::post('/ammos', [AmmoController::class, 'store']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

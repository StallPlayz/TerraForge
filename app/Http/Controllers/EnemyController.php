<?php

namespace App\Http\Controllers;

use App\Models\Enemy;
use Illuminate\Http\Request;

class EnemyController extends Controller
{
    /**
     * Display a listing of the enemies.
     */
    public function index()
    {
        $enemies = Enemy::all();
        
        return response()->json($enemies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_defense' => 'required|integer',
        ]);

        Enemy::create($validated);

        return back();
    }
}
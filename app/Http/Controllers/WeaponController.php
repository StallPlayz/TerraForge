<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    /**
     * Display a listing of the weapons.
     */
    public function index()
    {
        $weapons = Weapon::all();

        return response()->json($weapons);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'damage_type' => 'required|string',
            'use_ammo_type' => 'nullable|string',
            'base_damage' => 'required|integer',
            'base_crit_chance' => 'required|integer',
            'armor_penetration' => 'required|integer',
            'use_time' => 'required|integer',
            'use_animation' => 'required|integer',
            'auto_reuse' => 'required|boolean',
            'is_true_melee' => 'required|boolean',
            'projectile_count' => 'required|integer',
            'mana_cost' => 'required|integer',
            'reuse_delay' => 'required|integer',
            'melee_speed_application' => 'required|string',
        ]);

        Weapon::create($validated);

        return back();
    }
}

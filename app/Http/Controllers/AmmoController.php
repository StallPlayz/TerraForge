<?php

namespace App\Http\Controllers;

use App\Models\Ammo;
use Illuminate\Http\Request;

class AmmoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ammo_type' => 'required|string|max:255',
            'damage' => 'required|integer',
            'armor_penetration' => 'required|integer',
        ]);

        Ammo::create($validated);

        return back();
    }
}
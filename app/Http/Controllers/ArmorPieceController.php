<?php

namespace App\Http\Controllers;

use App\Models\ArmorPiece;
use Illuminate\Http\Request;

class ArmorPieceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slot' => 'required|in:Head,Body,Legs',
            'defense' => 'required|integer',
            'stat_bonuses' => 'nullable|array',
            'set_family' => 'nullable|string|max:255',
            'set_bonus_description' => 'nullable|string',
            'set_bonus_stats' => 'nullable|array',
        ]);

        ArmorPiece::create($validated);

        return back();
    }
}

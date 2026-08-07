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
}
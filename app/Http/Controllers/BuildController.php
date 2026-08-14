<?php

namespace App\Http\Controllers;

use App\Models\Build;

class BuildController extends Controller
{
    /**
     * Display a listing of the builds.
     */
    public function index()
    {
        $builds = Build::all();

        return response()->json($builds);
    }
}

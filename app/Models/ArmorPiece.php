<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmorPiece extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slot',
        'defense',
        'stat_bonuses',
        'set_family',
        'set_bonus_description',
        'set_bonus_stats',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'stat_bonuses' => 'array',
        'set_bonus_stats' => 'array',
    ];
}

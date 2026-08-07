<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
        protected $fillable = [
        'name',
        'base_defense',
        'damage_reduction',
        'debuff_immunities',
        'bestiary_id',
        ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
        protected $casts = [
        'base_defense' => 'integer',
        'damage_reduction' => 'float',
        'debuff_immunities' => 'array',
        'bestiary_id' => 'integer',
        ];
}

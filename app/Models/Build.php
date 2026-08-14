<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'max_mana',
        'armor_slots',
        'accessory_slots',
        'ammo_slots',
        'active_buffs',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'max_mana' => 'integer',
        'armor_slots' => 'array',
        'accessory_slots' => 'array',
        'ammo_slots' => 'array',
        'active_buffs' => 'array',
    ];
}

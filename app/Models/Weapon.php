<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
   use HasFactory;

   /**
    * The attributes that are mass assignable. 
    * 
    * @var array
    */

   protected $fillable = [
    'name',
    'base_damage',
    'damage_type',
    'use_ammo_type',
    'projectile_count',
    'base_critical_chance',
    'armor_penetration',
    'use_time',
    'use_animation',
    'auto_reuse',
    'mana_cost',
    'melee_speed_application',
    'is_true_melee',
    'reuse_delay',
   ];

   /**
    * The attributes that should be cast to native types.
    * 
    * @var array
    */

    protected $casts = [
    'auto_reuse' => 'boolean',
    'is_true_melee' => 'boolean',
    'base_damage' => 'integer',
    'projectile_count' => 'integer',
    'base_critical_chance' => 'integer',
    'armor_penetration' => 'integer',
    'use_time' => 'integer',
    'use_animation' => 'integer',
    'mana_cost' => 'integer',
    'reuse_delay' => 'integer',
    ];
}

<?php

namespace Database\Seeders;

use App\Models\Weapon;
use App\Models\Enemy;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Weapon::create([
            'name' => 'Copper Shortsword',
            'base_damage' => 5,
            'damage_type' => 'Melee',
            'use_ammo_type' => null,
            'projectile_count' => 1,
            'base_crit_chance' => 4,
            'armor_penetration' => 0,
            'use_time' => 13,
            'use_animation' => 13,
            'auto_reuse' => false,
            'mana_cost' => 0,
            'melee_speed_application' => 'UseTime',
            'is_true_melee' => true,
            'reuse_delay' => 0,
        ]);

        Enemy::create([
            'name' => 'Blue Slime',
            'base_defense' => 2,
            'damage_reduction' => 0.0000, 
            'debuff_immunities' => ['Poisoned'],
            'bestiary_id' => 1,
        ]);
    }
}
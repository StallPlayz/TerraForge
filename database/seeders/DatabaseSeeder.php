<?php

namespace Database\Seeders;

use App\Models\Weapon;
use App\Models\Enemy;
use App\Models\ArmorPiece;
use App\Models\Accessory;
use App\Models\Ammo;
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

        Weapon::create([
            'name' => 'Minishark',
            'base_damage' => 6,
            'damage_type' => 'Ranged',
            'use_ammo_type' => 'Bullet',
            'projectile_count' => 1,
            'base_crit_chance' => 4,
            'armor_penetration' => 0,
            'use_time' => 8,
            'use_animation' => 8,
            'auto_reuse' => true,
            'mana_cost' => 0,
            'melee_speed_application' => 'None',
            'is_true_melee' => false,
            'reuse_delay' => 0,
        ]);

        Enemy::create([
            'name' => 'Blue Slime',
            'base_defense' => 2,
            'debuff_immunities' => ['Poisoned', 'Bleeding', 'Hemorrhage'],
            'bestiary_id' => 1,
        ]);

        Enemy::create([
            'name' => 'Eye Of Cthulhu',
            'base_defense' => 12,
            'debuff_immunities' => ['Confused'],
            'bestiary_id' => 2,
        ]);

        Ammo::create([
            'name' => 'Musket Ball',
            'ammo_type' => 'Bullet',
            'damage' => 7,
            'armor_penetration' => 0
        ]);

        Ammo::create([
            'name' => 'Silver Bullet',
            'ammo_type' => 'Bullet',
            'damage' => 9,
            'armor_penetration' => 0
        ]);

        Accessory::create([
            'name' => 'Shark Tooth Necklace',
            'description' => 'Increases armor penetration by 5',
            'stat_bonuses' => ['armor_penetration' => 5]
        ]);

        Accessory::create([
            'name' => 'Ranger Emblem',
            'description' => '15% increased ranged damage',
            'stat_bonuses' => ['ranged_damage' => 0.15]
        ]);

        ArmorPiece::create([
            'name' => 'Necro Helmet',
            'slot' => 'Head',
            'defense' => 5,
            'stat_bonuses' => ['ranged_crit' => 5],
            'set_family' => 'Necro',
            'set_bonus_description' => '10% increased ranged damage',
            'set_bonus_stats' => ['ranged_damage' => 0.10]
        ]);

        ArmorPiece::create([
            'name' => 'Necro Breastplate',
            'slot' => 'Body',
            'defense' => 6,
            'stat_bonuses' => ['ranged_damage' => 0.05],
            'set_family' => 'Necro'
        ]);

        ArmorPiece::create([
            'name' => 'Necro Greaves',
            'slot' => 'Legs',
            'defense' => 5,
            'stat_bonuses' => ['ranged_damage' => 0.05],
            'set_family' => 'Necro'
        ]);
    }
}

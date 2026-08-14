<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->integer('base_damage');
            $table->enum('damage_type', ['Melee', 'Ranged', 'Magic', 'Summon']);
            $table->string('use_ammo_type')->nullable();
            $table->integer('projectile_count')->default(1);
            $table->integer('base_crit_chance');
            $table->integer('armor_penetration')->default(0);

            $table->integer('use_time');
            $table->integer('use_animation');
            $table->boolean('auto_reuse')->default(false);
            $table->integer('mana_cost')->default(0);

            $table->enum('melee_speed_application', ['None', 'UseTime', 'WhipScale']);
            $table->boolean('is_true_melee')->default(false);
            $table->integer('reuse_delay')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};

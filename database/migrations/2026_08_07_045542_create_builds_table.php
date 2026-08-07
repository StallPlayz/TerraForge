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
        Schema::create('builds', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            
            $table->integer('max_mana')->default(20);

            $table->json('armor_slots')->nullable();
            $table->json('accessory_slots')->nullable();
            $table->json('ammo_slots')->nullable();
            $table->json('active_buffs')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};

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
        Schema::create('ammos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ammo_type');
            $table->integer('damage')->default(0);
            $table->integer('armor_penetration')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ammos');
    }
};

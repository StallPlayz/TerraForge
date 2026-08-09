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
        Schema::create('armor_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('armor_type', ['Head', 'Body', 'Legs']);
            $table->integer('defense')->default(0);
            $table->json('stat_bonuses')->nullable();

            $table->string('set_family')->nullable();
            $table->text('set_bonus_description')->nullable();
            $table->json('set_bonus_stats')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armor_pieces');
    }
};

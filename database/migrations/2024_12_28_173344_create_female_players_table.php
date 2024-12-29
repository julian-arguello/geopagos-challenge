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
        Schema::create('female_players', function (Blueprint $table) {
            $table->foreignId('player_id')->primary()->nullable(false)->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('reaction_time')->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('female_players');
    }
};

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
        Schema::create('male_players', function (Blueprint $table) {
            $table->foreignId('player_id')->primary()->nullable(false)->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('stength')->nullable(false)->default(0);
            $table->float('movement_speed')->nullable(false)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('male_players');
    }
};

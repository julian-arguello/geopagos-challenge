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
        Schema::create('player_tournament', function (Blueprint $table) {
            $table->primary(['player_id', 'tournament_id']);
            $table->foreignId('player_id')->nullable(false)->constrained();
            $table->foreignId('tournament_id')->nullable(false)->constrained();
            $table->boolean('is_winner')->default(false);
            $table->foreignId('last_opponent_id')->nullable()->constrained('players');
            $table->integer('last_round')->nullable(false)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_tournament');
    }
};

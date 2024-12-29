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
            $table->primary(['player_id','tournament_id']);
            $table->foreignId('player_id')->nullable(false)->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->nullable(false)->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('position')->nullable(false)->default(0);
            $table->timestamps();
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
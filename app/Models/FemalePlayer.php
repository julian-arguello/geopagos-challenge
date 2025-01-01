<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FemalePlayer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['reaction_time', 'player_id'];

    const MAX_REACTION_TIME = 50;
    const MIN_REACTION_TIME = 2500;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public static function createAndAssignToTournament(array $playerData, Tournament $tournament)
    {

        $player = Player::create([
            'name' => $playerData['name'],
            'skill_level' => $playerData['skill_level'],
        ]);

        return self::create([
            'reaction_time' => $playerData['reaction_time'],
            'stength' => $player->id
        ]);

        $tournament->players()->attach($player->id);

        return $malePlayer;
    }
}

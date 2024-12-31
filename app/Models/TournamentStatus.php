<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentStatus extends Model
{
    use HasFactory, SoftDeletes;

    const NO_PLAYERS = 1;
    const PLAYERS_COUNT_MUST_BE_A_POWER_OF_2 = 2;
    const PLAYABLE = 3;
    const IN_PROGRESS = 4;
    const FINISHED = 5;

    public function Tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }


    public static function getTournamentStatusOptions()
    {
        return [
            'NO_PLAYERS' => self::NO_PLAYERS,
            'PLAYERS_COUNT_MUST_BE_A_POWER_OF_2' => self::PLAYERS_COUNT_MUST_BE_A_POWER_OF_2,
            'PLAYABLE' => self::PLAYABLE,
            'IN_PROGRESS' => self::IN_PROGRESS,
            'FINISHED' => self::FINISHED
        ];
    }
}

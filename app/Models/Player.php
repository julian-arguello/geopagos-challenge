<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    const MAX_SKILL_LEVEL = 100;
    const MIN_SKILL_LEVEL = 0;

    public function malePlayer(): HasOne
    {
        return $this->hasOne(MalePlayer::class);
    }

    public function femalePlayer(): HasOne
    {
        return $this->hasOne(FemalePlayer::class);
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class)->withPivot('is_winner', 'last_opponent_id', 'last_round');
    }

    public function playersTournaments(): HasMany
    {
        return $this->hasMany(PlayerTournament::class);
    }
}

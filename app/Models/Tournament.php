<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
    use HasFactory, SoftDeletes;

    public function status(): BelongsTo
    {
        return $this->belongsTo(TournamentStatus::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->withPivot('is_winner', 'last_opponent_id', 'last_round');
    }

    public function playersOrderedByLastRound(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->withPivot('is_winner', 'last_opponent_id', 'last_round')
            ->orderBy('pivot_is_winner', 'desc')
            ->orderBy('pivot_last_round', 'desc')
            ->orderBy('name', 'asc');;
    }
}

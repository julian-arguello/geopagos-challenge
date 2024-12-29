<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerTournament extends Pivot
{
    use HasFactory, SoftDeletes;

    public function lastOpponent()
    {
        return $this->belongsTo(Player::class, 'last_opponent_id');
    }
}

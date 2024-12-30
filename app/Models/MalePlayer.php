<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MalePlayer extends Model
{
    use HasFactory, SoftDeletes;

    const MAX_STENGTH = 2500; // n 
    const MIN_STENGTH = 0; // n 
    const MAX_MOVEMENT_SPEED = 10.0; // m/s
    const MIN_MOVEMENT_SPEED = 0.0; // m/s

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}

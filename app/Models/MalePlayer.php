<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MalePlayer extends Model
{
    use HasFactory, SoftDeletes;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}

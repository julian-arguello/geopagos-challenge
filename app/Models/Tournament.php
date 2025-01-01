<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Tournament",
 *     type="object",
 *     description="Representación de un torneo",
 *     @OA\Property(property="id", type="integer", description="ID único del torneo", example=1),
 *     @OA\Property(property="gender_id", type="integer", description="ID del género asociado al torneo", example=1),
 *     @OA\Property(property="status_id", type="integer", description="ID del estado del torneo", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación del torneo", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización del torneo", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación del torneo", example=null),
 *     @OA\Property(property="status", ref="#/components/schemas/TournamentStatus", description="Estado del torneo"),
 *     @OA\Property(property="gender", ref="#/components/schemas/Gender", description="Género del torneo")
 * )
 */
class Tournament extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['gender_id', 'status_id'];


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

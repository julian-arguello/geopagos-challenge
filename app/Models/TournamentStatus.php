<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="TournamentStatus",
 *     type="object",
 *     description="Estado del torneo",
 *     @OA\Property(property="id", type="integer", description="ID único del estado", example=3),
 *     @OA\Property(property="code", type="string", description="Código del estado", example="Playable"),
 *     @OA\Property(property="title", type="string", description="Título del estado", example="Jugable"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación del estado", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización del estado", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación del estado", example=null)
 * )
 */
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

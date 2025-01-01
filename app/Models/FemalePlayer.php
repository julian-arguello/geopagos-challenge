<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="FemalePlayerData",
 *     type="object",
 *     description="Datos de una jugadora femenina",
 *     @OA\Property(property="id", type="integer", description="ID único del jugador", example=16),
 *     @OA\Property(property="name", type="string", description="Nombre de la jugadora", example="Mariana Reilly"),
 *     @OA\Property(property="skill_level", type="integer", description="Nivel de habilidad de la jugadora", example=18),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación", example=null),
 *     @OA\Property(
 *         property="female_player",
 *         type="object",
 *         description="Datos específicos de la jugadora femenina",
 *         @OA\Property(property="player_id", type="integer", description="ID del jugador asociado", example=16),
 *         @OA\Property(property="reaction_time", type="integer", description="Tiempo de reacción de la jugadora", example=2216),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación", example="2025-01-01T16:31:13.000000Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización", example="2025-01-01T16:31:13.000000Z"),
 *         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación", example=null)
 *     )
 * )
 */
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

        $femalePlayer = self::create([
            'reaction_time' => $playerData['reaction_time'],
            'player_id' => $player->id
        ]);

        $tournament->players()->attach($player->id);

        return $femalePlayer;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="MalePlayerData",
 *     type="object",
 *     description="Datos de un jugador masculino",
 *     @OA\Property(property="id", type="integer", description="ID único del jugador", example=1),
 *     @OA\Property(property="name", type="string", description="Nombre del jugador", example="Julián Argüello"),
 *     @OA\Property(property="skill_level", type="integer", description="Nivel de habilidad del jugador", example=92),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación", example=null),
 *     @OA\Property(
 *         property="male_player",
 *         type="object",
 *         description="Datos específicos del jugador masculino",
 *         @OA\Property(property="player_id", type="integer", description="ID del jugador asociado", example=1),
 *         @OA\Property(property="stength", type="integer", description="Fuerza del jugador", example=228),
 *         @OA\Property(property="movement_speed", type="number", format="float", description="Velocidad de movimiento del jugador", example=9.34),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación", example="2025-01-01T16:31:13.000000Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización", example="2025-01-01T16:31:13.000000Z"),
 *         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación", example=null)
 *     )
 * )
 */
class MalePlayer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['stength', 'movement_speed', 'player_id'];

    const MAX_STENGTH = 2500; // n 
    const MIN_STENGTH = 0; // n 
    const MAX_MOVEMENT_SPEED = 10.0; // m/s
    const MIN_MOVEMENT_SPEED = 0.0; // m/s

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

        $malePlayer = self::create([
            'stength' => $playerData['stength'],
            'movement_speed' => $playerData['movement_speed'],
            'player_id' => $player->id,
        ]);

        $tournament->players()->attach($player->id);

        return $malePlayer;
    }
}

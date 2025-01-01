<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Gender",
 *     type="object",
 *     description="Géneros",
 *     @OA\Property(property="id", type="integer", description="ID único del género", example=1),
 *     @OA\Property(property="code", type="string", description="Código del género", example="Male"),
 *     @OA\Property(property="title", type="string", description="Título del género", example="Masculino"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación del género", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización del género", example="2025-01-01T16:31:13.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Fecha de eliminación del género", example=null)
 * )
 */
class Gender extends Model
{
    use HasFactory, SoftDeletes;

    const MALE_ID = 1;
    const FEMALE_ID = 2;

    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public static function getGenderOptions()
    {
        return [
            'MALE' => self::MALE_ID,
            'FEMALE' => self::FEMALE_ID,
        ];
    }
}

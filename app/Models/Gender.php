<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

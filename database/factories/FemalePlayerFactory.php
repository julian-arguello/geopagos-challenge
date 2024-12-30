<?php

namespace Database\Factories;

use App\Models\FemalePlayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FemalePlayer>
 */
class FemalePlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reaction_time' => $this->faker->numberBetween(FemalePlayer::MIN_REACTION_TIME, FemalePlayer::MAX_REACTION_TIME)
        ];
    }
}

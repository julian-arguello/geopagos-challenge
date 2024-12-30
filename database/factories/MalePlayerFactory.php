<?php

namespace Database\Factories;

use App\Models\MalePlayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MalePlayer>
 */
class MalePlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stength' => $this->faker->numberBetween(MalePlayer::MIN_STENGTH, MalePlayer::MAX_STENGTH),
            'movement_speed' => $this->faker->randomFloat(2, MalePlayer::MIN_MOVEMENT_SPEED, MalePlayer::MAX_MOVEMENT_SPEED)
        ];
    }
}

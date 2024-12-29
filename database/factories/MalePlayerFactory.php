<?php

namespace Database\Factories;

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
            'stength' => $this->faker->numberBetween(0, 200),
            'movement_speed' => $this->faker->randomFloat(2, 1, 30)
        ];
    }
}

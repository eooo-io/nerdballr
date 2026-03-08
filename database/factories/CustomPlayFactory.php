<?php

namespace Database\Factories;

use App\Models\CustomPlay;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CustomPlay> */
class CustomPlayFactory extends Factory
{
    protected $model = CustomPlay::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->words(3, true),
            'category' => fake()->optional(0.5)->randomElement(['formation-offense', 'formation-defense', 'route-concept']),
            'tags' => fake()->randomElements(['custom', 'practice', 'game-plan'], rand(0, 2)),
            'roster' => [
                ['id' => 'QB1', 'role' => 'QB', 'label' => 'QB', 'side' => 'offense'],
            ],
            'phases' => [
                [
                    'id' => 0,
                    'label' => 'Setup',
                    'description' => 'Initial positions',
                    'durationMs' => 1000,
                    'players' => [
                        ['playerId' => 'QB1', 'position' => ['x' => 600, 'y' => 266]],
                    ],
                ],
            ],
            'is_public' => false,
            'thumbnail' => null,
        ];
    }
}

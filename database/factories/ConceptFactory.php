<?php

namespace Database\Factories;

use App\Models\Concept;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Concept> */
class ConceptFactory extends Factory
{
    protected $model = Concept::class;

    public function definition(): array
    {
        $label = fake()->unique()->words(3, true);
        $categories = [
            'formation-offense', 'formation-defense', 'coverage',
            'blitz', 'route-concept', 'pocket-mechanics',
            'ball-physics', 'geometry',
        ];

        return [
            'slug' => Str::slug($label),
            'label' => ucwords($label),
            'category' => fake()->randomElement($categories),
            'subcategory' => fake()->optional(0.5)->word(),
            'tags' => fake()->randomElements(['zone', 'man', 'press', 'blitz', 'screen', 'run', 'pass'], rand(1, 3)),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'layers' => fake()->randomElements([1, 2, 3, 4], rand(1, 2)),
            'description' => fake()->sentence(),
            'explanation' => fake()->paragraphs(3, true),
            'roster' => [
                ['id' => 'QB1', 'role' => 'QB', 'label' => 'QB', 'side' => 'offense'],
                ['id' => 'RB1', 'role' => 'RB', 'label' => 'RB', 'side' => 'offense'],
                ['id' => 'WR1', 'role' => 'WR', 'label' => 'X', 'side' => 'offense'],
            ],
            'phases' => [
                [
                    'id' => 0,
                    'label' => 'Pre-snap',
                    'description' => 'Initial alignment',
                    'durationMs' => 1000,
                    'players' => [
                        ['playerId' => 'QB1', 'position' => ['x' => 600, 'y' => 266]],
                        ['playerId' => 'RB1', 'position' => ['x' => 570, 'y' => 266]],
                        ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 50]],
                    ],
                ],
                [
                    'id' => 1,
                    'label' => 'Post-snap',
                    'description' => 'Route development',
                    'durationMs' => 2000,
                    'players' => [
                        ['playerId' => 'QB1', 'position' => ['x' => 580, 'y' => 266]],
                        ['playerId' => 'RB1', 'position' => ['x' => 550, 'y' => 200]],
                        ['playerId' => 'WR1', 'position' => ['x' => 700, 'y' => 50]],
                    ],
                ],
            ],
            'counters' => [],
            'related' => [],
            'ai_context' => fake()->paragraph(),
        ];
    }

    public function offense(): static
    {
        return $this->state(fn () => ['category' => 'formation-offense']);
    }

    public function defense(): static
    {
        return $this->state(fn () => ['category' => 'formation-defense']);
    }

    public function coverage(): static
    {
        return $this->state(fn () => ['category' => 'coverage']);
    }
}

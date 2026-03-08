<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<UserProgress> */
class UserProgressFactory extends Factory
{
    protected $model = UserProgress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'concept_id' => Concept::factory(),
            'completed_at' => now(),
        ];
    }
}

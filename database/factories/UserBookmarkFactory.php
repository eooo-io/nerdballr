<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\User;
use App\Models\UserBookmark;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<UserBookmark> */
class UserBookmarkFactory extends Factory
{
    protected $model = UserBookmark::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'concept_id' => Concept::factory(),
            'created_at' => now(),
        ];
    }
}

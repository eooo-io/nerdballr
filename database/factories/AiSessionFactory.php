<?php

namespace Database\Factories;

use App\Models\AiSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<AiSession> */
class AiSessionFactory extends Factory
{
    protected $model = AiSession::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_key' => Str::random(64),
            'messages' => [],
            'concept_ids' => [],
        ];
    }

    public function guest(): static
    {
        return $this->state(fn () => ['user_id' => null]);
    }

    public function withMessages(int $count = 3): static
    {
        $messages = [];
        for ($i = 0; $i < $count; $i++) {
            $messages[] = [
                'role' => $i % 2 === 0 ? 'user' : 'assistant',
                'content' => fake()->sentence(),
            ];
        }

        return $this->state(fn () => ['messages' => $messages]);
    }
}

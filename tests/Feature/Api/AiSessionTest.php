<?php

use App\Models\AiSession;
use App\Models\User;

it('retrieves session by key for guest', function () {
    $session = AiSession::factory()->guest()->create([
        'messages' => [
            ['role' => 'user', 'content' => 'What is Cover 2?'],
            ['role' => 'assistant', 'content' => 'Cover 2 is a zone defense.'],
        ],
    ]);

    $response = $this->getJson("/api/ai/session/{$session->session_key}");

    $response->assertOk()
        ->assertJsonPath('data.session_key', $session->session_key)
        ->assertJsonCount(2, 'data.messages');
});

it('retrieves session by key for authenticated user', function () {
    $user = User::factory()->create();
    $session = AiSession::factory()->create([
        'user_id' => $user->id,
        'messages' => [
            ['role' => 'user', 'content' => 'Explain Cover 3'],
        ],
    ]);

    $response = $this->actingAs($user)->getJson("/api/ai/session/{$session->session_key}");

    $response->assertOk()
        ->assertJsonPath('data.session_key', $session->session_key);
});

it('denies access to another user session', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $session = AiSession::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson("/api/ai/session/{$session->session_key}");

    $response->assertNotFound();
});

it('returns 404 for nonexistent session key', function () {
    $response = $this->getJson('/api/ai/session/'.str_repeat('f', 64));

    $response->assertNotFound();
});

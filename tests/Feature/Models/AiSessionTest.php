<?php

use App\Models\AiSession;
use App\Models\User;

it('creates an ai session via factory', function () {
    $session = AiSession::factory()->create();

    expect($session)->toBeInstanceOf(AiSession::class)
        ->and($session->id)->toMatch('/^[0-9a-f]{8}-/')
        ->and($session->session_key)->toHaveLength(64)
        ->and($session->user)->toBeInstanceOf(User::class);
});

it('can create a guest session without user', function () {
    $session = AiSession::factory()->guest()->create();

    expect($session->user_id)->toBeNull()
        ->and($session->user)->toBeNull();
});

it('casts JSON columns to arrays', function () {
    $session = AiSession::factory()->create();

    expect($session->messages)->toBeArray()
        ->and($session->concept_ids)->toBeArray();
});

it('stores messages with correct structure', function () {
    $session = AiSession::factory()->withMessages(4)->create();

    expect($session->messages)->toHaveCount(4)
        ->and($session->messages[0])->toHaveKeys(['role', 'content'])
        ->and($session->messages[0]['role'])->toBe('user')
        ->and($session->messages[1]['role'])->toBe('assistant');
});

it('enforces unique session key', function () {
    AiSession::factory()->create(['session_key' => str_repeat('a', 64)]);
    AiSession::factory()->create(['session_key' => str_repeat('a', 64)]);
})->throws(\Illuminate\Database\QueryException::class);

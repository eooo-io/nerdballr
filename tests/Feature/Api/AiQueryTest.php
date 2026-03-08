<?php

use App\Models\AiSession;
use App\Models\Concept;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                ['type' => 'text', 'text' => 'Cover 2 is a zone defense with two deep safeties.'],
            ],
        ]),
    ]);

    RateLimiter::clear('ai_query:guest:anonymous:127.0.0.1');
});

it('returns AI response for valid query with concepts', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertOk()
        ->assertJsonPath('data.response', 'Cover 2 is a zone defense with two deep safeties.')
        ->assertJsonPath('data.intent', 'explain')
        ->assertJsonPath('data.concepts_used', ['cover-2']);

    expect($response->json('session_key'))->toHaveLength(64);
});

it('returns decline when no concepts match', function () {
    $response = $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['nonexistent-concept'],
    ]);

    $response->assertOk()
        ->assertJsonPath('data.concepts_used', []);

    expect($response->json('data.response'))->toContain('don\'t have any matching concepts');

    Http::assertNothingSent();
});

it('detects explain intent', function () {
    Concept::factory()->create(['slug' => 'cover-3']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'Explain Cover 3 zone',
        'concept_slugs' => ['cover-3'],
    ]);

    $response->assertJsonPath('data.intent', 'explain');
});

it('detects compare intent', function () {
    Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'Cover 2 vs Cover 3',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertJsonPath('data.intent', 'compare');
});

it('detects counter intent', function () {
    Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'How do you beat Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertJsonPath('data.intent', 'counter');
});

it('detects pre-snap-read intent', function () {
    Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'What should the QB see against Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertJsonPath('data.intent', 'pre-snap-read');
});

it('detects recommend intent', function () {
    Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'What should I study next?',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertJsonPath('data.intent', 'recommend');
});

it('validates query is required', function () {
    $response = $this->postJson('/api/ai/query', [
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['query']);
});

it('validates concept_slugs is required', function () {
    $response = $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_slugs']);
});

it('validates query max length', function () {
    $response = $this->postJson('/api/ai/query', [
        'query' => str_repeat('a', 501),
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['query']);
});

it('validates concept_slugs max count', function () {
    $response = $this->postJson('/api/ai/query', [
        'query' => 'test',
        'concept_slugs' => array_fill(0, 11, 'slug'),
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_slugs']);
});

it('persists conversation in session', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    $sessionKey = $response->json('session_key');
    $session = AiSession::where('session_key', $sessionKey)->first();

    expect($session)->not->toBeNull()
        ->and($session->messages)->toHaveCount(2)
        ->and($session->messages[0]['role'])->toBe('user')
        ->and($session->messages[0]['content'])->toBe('What is Cover 2?')
        ->and($session->messages[1]['role'])->toBe('assistant');
});

it('appends to existing session', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);
    $sessionKey = bin2hex(random_bytes(32));

    $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['cover-2'],
        'session_key' => $sessionKey,
    ]);

    RateLimiter::clear("ai_query:guest:{$sessionKey}");

    $this->postJson('/api/ai/query', [
        'query' => 'Tell me more',
        'concept_slugs' => ['cover-2'],
        'session_key' => $sessionKey,
    ]);

    $session = AiSession::where('session_key', $sessionKey)->first();

    expect($session->messages)->toHaveCount(4);
});

it('rate limits guest users at 5 per session', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/ai/query', [
            'query' => "Query {$i}",
            'concept_slugs' => ['cover-2'],
        ])->assertOk();
    }

    $response = $this->postJson('/api/ai/query', [
        'query' => 'One too many',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertStatus(429)
        ->assertJsonStructure(['message', 'retry_after']);
});

it('rate limits authenticated users at 20 per hour', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    for ($i = 0; $i < 20; $i++) {
        RateLimiter::clear("ai_query:user:{$user->id}");
    }

    // Hit limit
    for ($i = 0; $i < 20; $i++) {
        $this->actingAs($user)->postJson('/api/ai/query', [
            'query' => "Query {$i}",
            'concept_slugs' => ['cover-2'],
        ])->assertOk();
    }

    $response = $this->actingAs($user)->postJson('/api/ai/query', [
        'query' => 'One too many',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertStatus(429);
});

it('includes rate limit remaining header', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    $response = $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    $response->assertOk();
    expect($response->headers->get('X-RateLimit-Remaining'))->not->toBeNull();
});

it('sends correct headers to Anthropic API', function () {
    $concept = Concept::factory()->create(['slug' => 'cover-2']);

    $this->postJson('/api/ai/query', [
        'query' => 'What is Cover 2?',
        'concept_slugs' => ['cover-2'],
    ]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.anthropic.com/v1/messages'
            && $request->hasHeader('x-api-key')
            && $request->hasHeader('anthropic-version', '2023-06-01')
            && $request['max_tokens'] === 600
            && str_contains($request['system'], 'football tactics instructor')
            && $request['messages'][0]['role'] === 'user';
    });
});

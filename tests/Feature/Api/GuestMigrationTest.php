<?php

use App\Models\AiSession;
use App\Models\Concept;
use App\Models\User;
use App\Models\UserBookmark;
use App\Models\UserProgress;

it('requires authentication', function () {
    $this->postJson('/api/user/migrate-guest')->assertUnauthorized();
});

it('migrates bookmarks from guest slugs', function () {
    $user = User::factory()->create();
    $c1 = Concept::factory()->create(['slug' => 'cover-2-defense']);
    $c2 = Concept::factory()->create(['slug' => 'shotgun-spread']);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'bookmarks' => ['cover-2-defense', 'shotgun-spread'],
    ]);

    $response->assertOk()
        ->assertJsonPath('bookmarks_created', 2)
        ->assertJsonPath('errors', []);

    expect(UserBookmark::where('user_id', $user->id)->count())->toBe(2);
});

it('migrates completed concepts from guest slugs', function () {
    $user = User::factory()->create();
    Concept::factory()->create(['slug' => 'i-formation']);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'completed' => ['i-formation'],
    ]);

    $response->assertOk()
        ->assertJsonPath('progress_created', 1);

    expect(UserProgress::where('user_id', $user->id)->count())->toBe(1);
});

it('transfers AI session ownership', function () {
    $user = User::factory()->create();
    $sessionKey = str_repeat('a', 64);
    AiSession::factory()->guest()->create(['session_key' => $sessionKey]);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'session_key' => $sessionKey,
    ]);

    $response->assertOk()
        ->assertJsonPath('session_transferred', true);

    expect(AiSession::where('session_key', $sessionKey)->first()->user_id)->toBe($user->id);
});

it('does not transfer already-owned AI session', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $sessionKey = str_repeat('b', 64);
    AiSession::factory()->create([
        'session_key' => $sessionKey,
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'session_key' => $sessionKey,
    ]);

    $response->assertOk()
        ->assertJsonPath('session_transferred', false);

    // Session should still belong to original owner
    expect(AiSession::where('session_key', $sessionKey)->first()->user_id)->toBe($otherUser->id);
});

it('reports errors for unknown concept slugs', function () {
    $user = User::factory()->create();
    Concept::factory()->create(['slug' => 'real-concept']);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'bookmarks' => ['real-concept', 'fake-concept'],
        'completed' => ['also-fake'],
    ]);

    $response->assertOk()
        ->assertJsonPath('bookmarks_created', 1)
        ->assertJsonPath('progress_created', 0);

    expect($response->json('errors'))->toContain('Concept not found: fake-concept')
        ->toContain('Concept not found: also-fake');
});

it('skips duplicate bookmarks on migration', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create(['slug' => 'existing-bookmark']);
    UserBookmark::factory()->create([
        'user_id' => $user->id,
        'concept_id' => $concept->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', [
        'bookmarks' => ['existing-bookmark'],
    ]);

    $response->assertOk()
        ->assertJsonPath('bookmarks_created', 0);

    expect(UserBookmark::where('user_id', $user->id)->count())->toBe(1);
});

it('handles empty migration request', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/migrate-guest', []);

    $response->assertOk()
        ->assertJsonPath('bookmarks_created', 0)
        ->assertJsonPath('progress_created', 0)
        ->assertJsonPath('session_transferred', false)
        ->assertJsonPath('errors', []);
});

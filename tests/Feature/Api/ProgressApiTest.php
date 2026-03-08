<?php

use App\Models\Concept;
use App\Models\User;
use App\Models\UserProgress;

it('requires authentication to list progress', function () {
    $this->getJson('/api/user/progress')->assertUnauthorized();
});

it('requires authentication to create progress', function () {
    $this->postJson('/api/user/progress')->assertUnauthorized();
});

it('lists user progress with concept data', function () {
    $user = User::factory()->create();
    $concepts = Concept::factory()->count(3)->create();

    foreach ($concepts as $concept) {
        UserProgress::factory()->create([
            'user_id' => $user->id,
            'concept_id' => $concept->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson('/api/user/progress');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'concept', 'completed_at'],
            ],
        ]);
});

it('marks a concept as complete', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/progress', [
        'concept_id' => $concept->id,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.concept.id', $concept->id);

    $this->assertDatabaseHas('user_progress', [
        'user_id' => $user->id,
        'concept_id' => $concept->id,
    ]);
});

it('returns existing progress on duplicate creation', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    $first = $this->actingAs($user)->postJson('/api/user/progress', [
        'concept_id' => $concept->id,
    ]);

    $second = $this->actingAs($user)->postJson('/api/user/progress', [
        'concept_id' => $concept->id,
    ]);

    $first->assertCreated();
    $second->assertOk();

    expect(UserProgress::where('user_id', $user->id)->count())->toBe(1);

    $first->assertJsonPath('data.id', $second->json('data.id'));
});

it('validates concept_id is required', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/progress', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_id']);
});

it('validates concept_id must exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/progress', [
        'concept_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_id']);
});

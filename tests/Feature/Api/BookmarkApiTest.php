<?php

use App\Models\Concept;
use App\Models\User;
use App\Models\UserBookmark;

it('requires authentication to list bookmarks', function () {
    $this->getJson('/api/user/bookmarks')->assertUnauthorized();
});

it('requires authentication to create a bookmark', function () {
    $this->postJson('/api/user/bookmarks')->assertUnauthorized();
});

it('requires authentication to delete a bookmark', function () {
    $this->deleteJson('/api/user/bookmarks/fake-id')->assertUnauthorized();
});

it('lists user bookmarks with concept data', function () {
    $user = User::factory()->create();
    $concepts = Concept::factory()->count(3)->create();

    foreach ($concepts as $concept) {
        UserBookmark::factory()->create([
            'user_id' => $user->id,
            'concept_id' => $concept->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson('/api/user/bookmarks');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'concept', 'created_at'],
            ],
        ]);
});

it('creates a bookmark', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/bookmarks', [
        'concept_id' => $concept->id,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.concept.id', $concept->id);

    $this->assertDatabaseHas('user_bookmarks', [
        'user_id' => $user->id,
        'concept_id' => $concept->id,
    ]);
});

it('returns existing bookmark on duplicate creation', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    $first = $this->actingAs($user)->postJson('/api/user/bookmarks', [
        'concept_id' => $concept->id,
    ]);

    $second = $this->actingAs($user)->postJson('/api/user/bookmarks', [
        'concept_id' => $concept->id,
    ]);

    $first->assertCreated();
    $second->assertOk();

    expect(UserBookmark::where('user_id', $user->id)->count())->toBe(1);

    $first->assertJsonPath('data.id', $second->json('data.id'));
});

it('validates concept_id is required', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/bookmarks', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_id']);
});

it('validates concept_id must exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/bookmarks', [
        'concept_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['concept_id']);
});

it('deletes a bookmark', function () {
    $user = User::factory()->create();
    $bookmark = UserBookmark::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/user/bookmarks/{$bookmark->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('user_bookmarks', ['id' => $bookmark->id]);
});

it('cannot delete another user bookmark', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $bookmark = UserBookmark::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->deleteJson("/api/user/bookmarks/{$bookmark->id}");

    $response->assertNotFound();
    $this->assertDatabaseHas('user_bookmarks', ['id' => $bookmark->id]);
});

<?php

use App\Models\AiSession;
use App\Models\Concept;
use App\Models\CustomPlay;
use App\Models\User;
use App\Models\UserBookmark;
use App\Models\UserProgress;

it('user has many bookmarks', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();
    UserBookmark::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    expect($user->bookmarks)->toHaveCount(1)
        ->and($user->bookmarks->first()->concept_id)->toBe($concept->id);
});

it('user has many progress records', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();
    UserProgress::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    expect($user->progress)->toHaveCount(1)
        ->and($user->progress->first()->concept_id)->toBe($concept->id);
});

it('user has many custom plays', function () {
    $user = User::factory()->create();
    CustomPlay::factory()->create(['user_id' => $user->id]);

    expect($user->customPlays)->toHaveCount(1)
        ->and($user->customPlays->first())->toBeInstanceOf(CustomPlay::class);
});

it('user has many ai sessions', function () {
    $user = User::factory()->create();
    AiSession::factory()->create(['user_id' => $user->id]);

    expect($user->aiSessions)->toHaveCount(1)
        ->and($user->aiSessions->first())->toBeInstanceOf(AiSession::class);
});

it('deleting user cascades to bookmarks', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();
    UserBookmark::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    $user->delete();

    expect(UserBookmark::count())->toBe(0);
});

it('deleting user cascades to progress', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();
    UserProgress::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    $user->delete();

    expect(UserProgress::count())->toBe(0);
});

it('deleting user cascades to custom plays', function () {
    $user = User::factory()->create();
    CustomPlay::factory()->create(['user_id' => $user->id]);

    $user->delete();

    expect(CustomPlay::count())->toBe(0);
});

it('deleting user nullifies ai sessions', function () {
    $user = User::factory()->create();
    $session = AiSession::factory()->create(['user_id' => $user->id]);

    $user->delete();

    expect(AiSession::find($session->id)->user_id)->toBeNull();
});

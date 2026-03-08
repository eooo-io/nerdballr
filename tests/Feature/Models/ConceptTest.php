<?php

use App\Models\Concept;
use App\Models\User;
use App\Models\UserBookmark;
use App\Models\UserProgress;

it('creates a concept via factory', function () {
    $concept = Concept::factory()->create();

    expect($concept)->toBeInstanceOf(Concept::class)
        ->and($concept->id)->toBeString()
        ->and($concept->slug)->toBeString()
        ->and($concept->label)->toBeString();
});

it('casts JSON columns to arrays', function () {
    $concept = Concept::factory()->create();

    expect($concept->tags)->toBeArray()
        ->and($concept->layers)->toBeArray()
        ->and($concept->roster)->toBeArray()
        ->and($concept->phases)->toBeArray()
        ->and($concept->counters)->toBeArray()
        ->and($concept->related)->toBeArray();
});

it('generates a UUID primary key', function () {
    $concept = Concept::factory()->create();

    expect($concept->id)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

it('enforces unique slug', function () {
    Concept::factory()->create(['slug' => 'cover-2']);

    Concept::factory()->create(['slug' => 'cover-2']);
})->throws(\Illuminate\Database\QueryException::class);

it('has many bookmarks', function () {
    $concept = Concept::factory()->create();
    $user = User::factory()->create();
    UserBookmark::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    expect($concept->bookmarks)->toHaveCount(1)
        ->and($concept->bookmarks->first())->toBeInstanceOf(UserBookmark::class);
});

it('has many progress records', function () {
    $concept = Concept::factory()->create();
    $user = User::factory()->create();
    UserProgress::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);

    expect($concept->progress)->toHaveCount(1)
        ->and($concept->progress->first())->toBeInstanceOf(UserProgress::class);
});

it('stores roster with correct structure', function () {
    $concept = Concept::factory()->create();

    expect($concept->roster)->toBeArray()
        ->and($concept->roster[0])->toHaveKeys(['id', 'role', 'label', 'side']);
});

it('stores phases with correct structure', function () {
    $concept = Concept::factory()->create();

    expect($concept->phases)->toBeArray()
        ->and($concept->phases[0])->toHaveKeys(['id', 'label', 'description', 'durationMs', 'players']);
});

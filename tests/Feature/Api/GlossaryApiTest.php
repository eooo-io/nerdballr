<?php

use App\Models\GlossaryTerm;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    GlossaryTerm::create([
        'term' => 'Quarterback',
        'slug' => 'quarterback',
        'definition' => 'The offensive player who receives the snap.',
        'category' => 'offense',
        'related_terms' => ['snap', 'audible'],
        'related_concepts' => ['shotgun-spread'],
    ]);
    GlossaryTerm::create([
        'term' => 'Cover 3',
        'slug' => 'cover-3',
        'definition' => 'Zone coverage with three deep defenders.',
        'category' => 'scheme',
        'related_terms' => ['zone-coverage'],
        'related_concepts' => ['cover-3'],
    ]);
    GlossaryTerm::create([
        'term' => 'Blitz',
        'slug' => 'blitz',
        'definition' => 'A defensive play with extra pass rushers.',
        'category' => 'defense',
        'related_terms' => ['pass-rush'],
        'related_concepts' => ['zone-blitz', 'a-gap-blitz'],
    ]);
    GlossaryTerm::create([
        'term' => 'Down',
        'slug' => 'down',
        'definition' => 'One of four attempts to advance ten yards.',
        'category' => 'general',
        'related_terms' => ['first-down'],
        'related_concepts' => [],
    ]);
});

it('lists all glossary terms sorted alphabetically', function () {
    $response = $this->getJson('/api/glossary');

    $response->assertOk()
        ->assertJsonCount(4, 'data');

    $terms = collect($response->json('data'))->pluck('term')->toArray();
    expect($terms)->toBe(['Blitz', 'Cover 3', 'Down', 'Quarterback']);
});

it('filters glossary terms by category', function () {
    $response = $this->getJson('/api/glossary?category=offense');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.term', 'Quarterback');
});

it('searches glossary terms by term name', function () {
    $response = $this->getJson('/api/glossary?q=quarter');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.term', 'Quarterback');
});

it('searches glossary terms by definition', function () {
    $response = $this->getJson('/api/glossary?q=deep defenders');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.term', 'Cover 3');
});

it('combines category filter with search', function () {
    $response = $this->getJson('/api/glossary?category=defense&q=rush');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'blitz');
});

it('returns empty data when no terms match', function () {
    $response = $this->getJson('/api/glossary?q=nonexistent');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

it('shows a single glossary term by slug', function () {
    $response = $this->getJson('/api/glossary/quarterback');

    $response->assertOk()
        ->assertJsonPath('data.term', 'Quarterback')
        ->assertJsonPath('data.slug', 'quarterback')
        ->assertJsonPath('data.category', 'offense')
        ->assertJsonPath('data.related_terms', ['snap', 'audible'])
        ->assertJsonPath('data.related_concepts', ['shotgun-spread']);
});

it('returns 404 for missing glossary term', function () {
    $this->getJson('/api/glossary/nonexistent')
        ->assertNotFound();
});

it('rejects invalid category filter', function () {
    $this->getJson('/api/glossary?category=invalid')
        ->assertUnprocessable();
});

it('returns related_terms and related_concepts as arrays', function () {
    $response = $this->getJson('/api/glossary/blitz');

    $data = $response->json('data');
    expect($data['related_terms'])->toBeArray()
        ->and($data['related_concepts'])->toBeArray()
        ->and($data['related_concepts'])->toHaveCount(2);
});

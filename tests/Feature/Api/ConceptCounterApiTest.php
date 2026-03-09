<?php

use App\Models\Concept;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // cover-3 is countered by four-verts and mesh-concept
    $this->cover3 = Concept::factory()->create([
        'slug' => 'cover-3',
        'counters' => [],
    ]);

    $this->fourVerts = Concept::factory()->create([
        'slug' => 'four-verts',
        'counters' => ['cover-3'],
    ]);

    $this->meshConcept = Concept::factory()->create([
        'slug' => 'mesh-concept',
        'counters' => ['cover-3'],
    ]);

    // four-verts is countered by cover-2, which itself has no counters listed
    $this->cover2 = Concept::factory()->create([
        'slug' => 'cover-2',
        'counters' => ['four-verts'],
    ]);
});

it('returns counters for a concept that has them', function () {
    $response = $this->getJson('/api/concepts/four-verts/counters');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'counters' => [
                    '*' => ['id', 'slug', 'label', 'category', 'difficulty', 'description'],
                ],
                'countered_by',
            ],
        ]);

    $slugs = collect($response->json('data.counters'))->pluck('slug');
    expect($slugs)->toContain('cover-3');
});

it('returns countered_by via reverse lookup', function () {
    $response = $this->getJson('/api/concepts/cover-3/counters');

    $response->assertOk();

    $counteredBySlugs = collect($response->json('data.countered_by'))->pluck('slug');
    expect($counteredBySlugs)->toContain('four-verts')
        ->and($counteredBySlugs)->toContain('mesh-concept');
});

it('does not include the concept itself in countered_by', function () {
    // Create a pathological case where a concept lists itself
    $selfRef = Concept::factory()->create([
        'slug' => 'self-ref',
        'counters' => ['self-ref'],
    ]);

    $response = $this->getJson('/api/concepts/self-ref/counters');

    $response->assertOk();

    $counteredBySlugs = collect($response->json('data.countered_by'))->pluck('slug');
    expect($counteredBySlugs)->not->toContain('self-ref');
});

it('returns empty arrays when concept has no counters and is not countered', function () {
    $isolated = Concept::factory()->create([
        'slug' => 'isolated-concept',
        'counters' => [],
    ]);

    $response = $this->getJson('/api/concepts/isolated-concept/counters');

    $response->assertOk()
        ->assertJsonPath('data.counters', [])
        ->assertJsonPath('data.countered_by', []);
});

it('returns 404 for a missing concept slug', function () {
    $response = $this->getJson('/api/concepts/nonexistent-slug/counters');

    $response->assertNotFound();
});

it('counter results use summary resource shape without phases or roster', function () {
    $response = $this->getJson('/api/concepts/cover-3/counters');

    $response->assertOk()
        ->assertJsonMissingPath('data.countered_by.0.phases')
        ->assertJsonMissingPath('data.countered_by.0.roster')
        ->assertJsonMissingPath('data.countered_by.0.ai_context');
});

it('is publicly accessible without authentication', function () {
    $response = $this->getJson('/api/concepts/cover-3/counters');

    $response->assertOk();
});

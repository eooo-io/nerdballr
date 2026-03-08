<?php

use App\Models\Concept;

it('lists concepts with summary fields only', function () {
    Concept::factory()->count(3)->create();

    $response = $this->getJson('/api/concepts');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'label', 'category', 'subcategory', 'tags', 'difficulty', 'layers', 'description'],
            ],
            'links',
            'meta',
        ]);

    // Summary should not include phases or roster
    $response->assertJsonMissing(['phases']);
});

it('paginates concepts at 25 per page', function () {
    Concept::factory()->count(30)->create();

    $response = $this->getJson('/api/concepts');

    $response->assertOk()
        ->assertJsonCount(25, 'data')
        ->assertJsonPath('meta.per_page', 25)
        ->assertJsonPath('meta.total', 30);
});

it('filters concepts by category', function () {
    Concept::factory()->offense()->count(2)->create();
    Concept::factory()->defense()->count(3)->create();

    $response = $this->getJson('/api/concepts?category=formation-offense');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('filters concepts by tags', function () {
    Concept::factory()->create(['tags' => ['zone', 'pass']]);
    Concept::factory()->create(['tags' => ['man', 'press']]);
    Concept::factory()->create(['tags' => ['zone', 'run']]);

    $response = $this->getJson('/api/concepts?tags=zone');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('filters concepts by multiple tags', function () {
    Concept::factory()->create(['tags' => ['zone', 'pass']]);
    Concept::factory()->create(['tags' => ['zone', 'run']]);
    Concept::factory()->create(['tags' => ['man', 'press']]);

    $response = $this->getJson('/api/concepts?tags=zone,pass');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('searches concepts by keyword in label', function () {
    Concept::factory()->create(['label' => 'Cover 3 Zone']);
    Concept::factory()->create(['label' => 'Shotgun Formation']);

    $response = $this->getJson('/api/concepts?q=Cover');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.label', 'Cover 3 Zone');
});

it('searches concepts by keyword in description', function () {
    Concept::factory()->create(['description' => 'A deep zone coverage scheme']);
    Concept::factory()->create(['description' => 'Man-to-man press coverage']);

    $response = $this->getJson('/api/concepts?q=deep zone');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('shows a full concept by slug', function () {
    $concept = Concept::factory()->create();

    $response = $this->getJson("/api/concepts/{$concept->slug}");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id', 'slug', 'label', 'category', 'subcategory', 'tags',
                'difficulty', 'layers', 'description', 'explanation',
                'roster', 'phases', 'counters', 'related', 'ai_context',
                'created_at', 'updated_at',
            ],
        ])
        ->assertJsonPath('data.slug', $concept->slug);
});

it('returns 404 for missing concept slug', function () {
    $response = $this->getJson('/api/concepts/nonexistent-slug');

    $response->assertNotFound();
});

it('concept index is publicly accessible without auth', function () {
    $response = $this->getJson('/api/concepts');

    $response->assertOk();
});

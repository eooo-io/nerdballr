<?php

use App\Models\Concept;
use Database\Seeders\ConceptSeeder;

// ─── Difficulty Sort ──────────────────────────────────────────────────────────

it('sorts concepts by difficulty by default', function () {
    (new ConceptSeeder)->run();

    $response = $this->getJson('/api/concepts?sort=difficulty');

    $response->assertOk();

    $difficulties = collect($response->json('data'))->pluck('difficulty');

    // All beginner entries must appear before any intermediate or advanced
    $firstIntermediate = $difficulties->search('intermediate');
    $firstAdvanced = $difficulties->search('advanced');
    $lastBeginner = $difficulties->keys()->last(fn ($i) => $difficulties[$i] === 'beginner');

    if ($firstIntermediate !== false && $lastBeginner !== false) {
        expect($lastBeginner)->toBeLessThan($firstIntermediate);
    }

    if ($firstAdvanced !== false && $firstIntermediate !== false) {
        expect($firstIntermediate)->toBeLessThan($firstAdvanced);
    }

    // Sanity: first result must be beginner
    expect($difficulties->first())->toBe('beginner');
});

it('sorts concepts by difficulty when no sort parameter is given', function () {
    (new ConceptSeeder)->run();

    $response = $this->getJson('/api/concepts');

    $response->assertOk();

    $difficulties = collect($response->json('data'))->pluck('difficulty')->values();

    // Find the last beginner index and the first advanced index
    $lastBeginner = $difficulties->keys()->last(fn ($i) => $difficulties[$i] === 'beginner');
    $firstAdvanced = $difficulties->search('advanced');

    if ($lastBeginner !== false && $firstAdvanced !== false) {
        expect($lastBeginner)->toBeLessThan($firstAdvanced);
    }

    expect($difficulties->first())->toBe('beginner');
});

it('sorts concepts alphabetically with sort=alpha', function () {
    Concept::factory()->create(['label' => 'Zone Blitz', 'difficulty' => 'advanced']);
    Concept::factory()->create(['label' => 'Alpha Coverage', 'difficulty' => 'beginner']);
    Concept::factory()->create(['label' => 'Man Coverage', 'difficulty' => 'intermediate']);

    $response = $this->getJson('/api/concepts?sort=alpha');

    $response->assertOk();

    $labels = collect($response->json('data'))->pluck('label')->values();

    expect($labels[0])->toBe('Alpha Coverage')
        ->and($labels[1])->toBe('Man Coverage')
        ->and($labels[2])->toBe('Zone Blitz');
});

it('sorts concepts by difficulty with explicit sort=difficulty parameter', function () {
    Concept::factory()->create(['label' => 'Advanced Concept', 'difficulty' => 'advanced']);
    Concept::factory()->create(['label' => 'Beginner Concept', 'difficulty' => 'beginner']);
    Concept::factory()->create(['label' => 'Middle Concept', 'difficulty' => 'intermediate']);

    $response = $this->getJson('/api/concepts?sort=difficulty');

    $response->assertOk();

    $difficulties = collect($response->json('data'))->pluck('difficulty')->values();

    expect($difficulties[0])->toBe('beginner')
        ->and($difficulties[1])->toBe('intermediate')
        ->and($difficulties[2])->toBe('advanced');
});

it('applies difficulty sort within a category filter', function () {
    // The ConceptSeeder has 5 coverage concepts:
    //   cover-zero   → intermediate
    //   cover-one    → beginner
    //   cover-two    → beginner
    //   cover-three  → beginner
    //   cover-four   → advanced
    (new ConceptSeeder)->run();

    $response = $this->getJson('/api/concepts?category=coverage');

    $response->assertOk();

    $data = $response->json('data');

    expect(count($data))->toBeGreaterThan(1);

    $difficulties = collect($data)->pluck('difficulty')->values();

    // The advanced concept must come after all beginner ones
    $lastBeginner = $difficulties->keys()->last(fn ($i) => $difficulties[$i] === 'beginner');
    $firstAdvanced = $difficulties->search('advanced');

    if ($lastBeginner !== false && $firstAdvanced !== false) {
        expect($lastBeginner)->toBeLessThan($firstAdvanced);
    }

    // First coverage concept must not be advanced
    expect($difficulties->first())->not->toBe('advanced');
});

it('rejects an invalid sort parameter', function () {
    $response = $this->getJson('/api/concepts?sort=invalid');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['sort']);
});

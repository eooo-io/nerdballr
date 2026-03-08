<?php

use App\Models\Concept;
use Database\Seeders\ConceptSeeder;

beforeEach(function () {
    (new ConceptSeeder)->run();
});

it('seeds at least 15 concepts', function () {
    expect(Concept::count())->toBeGreaterThanOrEqual(15);
});

it('covers all required categories', function () {
    $categories = Concept::pluck('category')->unique()->sort()->values()->all();

    expect($categories)->toContain('formation-offense')
        ->toContain('formation-defense')
        ->toContain('coverage')
        ->toContain('blitz')
        ->toContain('route-concept')
        ->toContain('geometry');
});

it('has valid category values', function () {
    $allowed = [
        'formation-offense', 'formation-defense', 'coverage',
        'blitz', 'route-concept', 'pocket-mechanics',
        'ball-physics', 'geometry',
    ];

    Concept::all()->each(function ($concept) use ($allowed) {
        expect($concept->category)->toBeIn($allowed);
    });
});

it('has unique slugs', function () {
    $slugs = Concept::pluck('slug');
    expect($slugs->count())->toBe($slugs->unique()->count());
});

it('has valid slug format', function () {
    Concept::all()->each(function ($concept) {
        expect($concept->slug)->toMatch('/^[a-z0-9\-]+$/');
    });
});

it('has valid difficulty levels', function () {
    $allowed = ['beginner', 'intermediate', 'advanced'];

    Concept::all()->each(function ($concept) use ($allowed) {
        expect($concept->difficulty)->toBeIn($allowed);
    });
});

it('has at least 2 players in each roster', function () {
    Concept::all()->each(function ($concept) {
        expect(count($concept->roster))->toBeGreaterThanOrEqual(2);
    });
});

it('has 11 players in full-team concepts', function () {
    $fullTeamCategories = [
        'formation-offense', 'formation-defense', 'coverage',
        'blitz', 'route-concept',
    ];

    Concept::whereIn('category', $fullTeamCategories)->each(function ($concept) {
        expect($concept->roster)->toHaveCount(11);
    });
});

it('has unique player IDs within each roster', function () {
    Concept::all()->each(function ($concept) {
        $playerIds = array_column($concept->roster, 'id');
        expect(count($playerIds))->toBe(count(array_unique($playerIds)));
    });
});

it('has valid player roles in rosters', function () {
    $allowed = ['QB', 'RB', 'WR', 'TE', 'OL', 'DL', 'LB', 'CB', 'S'];

    Concept::all()->each(function ($concept) use ($allowed) {
        foreach ($concept->roster as $player) {
            expect($player['role'])->toBeIn($allowed);
        }
    });
});

it('has sequential phase IDs starting at 0', function () {
    Concept::all()->each(function ($concept) {
        $ids = array_column($concept->phases, 'id');
        $expected = range(0, count($concept->phases) - 1);
        expect($ids)->toBe($expected);
    });
});

it('has at least 2 phases per concept', function () {
    Concept::all()->each(function ($concept) {
        expect(count($concept->phases))->toBeGreaterThanOrEqual(2);
    });
});

it('has player positions within field bounds', function () {
    Concept::all()->each(function ($concept) {
        foreach ($concept->phases as $phase) {
            foreach ($phase['players'] as $ps) {
                expect($ps['position']['x'])->toBeGreaterThanOrEqual(0)->toBeLessThanOrEqual(1200);
                expect($ps['position']['y'])->toBeGreaterThanOrEqual(0)->toBeLessThanOrEqual(533);
            }
        }
    });
});

it('has valid counter references', function () {
    $allSlugs = Concept::pluck('slug')->all();

    Concept::all()->each(function ($concept) use ($allSlugs) {
        foreach ($concept->counters ?? [] as $counter) {
            expect($allSlugs)->toContain($counter);
        }
    });
});

it('has valid related references', function () {
    $allSlugs = Concept::pluck('slug')->all();

    Concept::all()->each(function ($concept) use ($allSlugs) {
        foreach ($concept->related ?? [] as $related) {
            expect($allSlugs)->toContain($related);
        }
    });
});

it('has ai_context for every concept', function () {
    Concept::all()->each(function ($concept) {
        expect($concept->ai_context)->not->toBeEmpty();
    });
});

it('has non-empty labels and descriptions', function () {
    Concept::all()->each(function ($concept) {
        expect($concept->label)->not->toBeEmpty();
        expect($concept->description)->not->toBeEmpty();
    });
});

it('is idempotent', function () {
    $countBefore = Concept::count();

    (new ConceptSeeder)->run();

    expect(Concept::count())->toBe($countBefore);
});

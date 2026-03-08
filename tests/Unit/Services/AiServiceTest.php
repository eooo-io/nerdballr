<?php

use App\Services\AiService;

it('detects explain intent', function () {
    $service = new AiService;

    expect($service->detectIntent('What is Cover 2?'))->toBe('explain')
        ->and($service->detectIntent('Explain the 4-3 defense'))->toBe('explain')
        ->and($service->detectIntent('How does zone coverage work?'))->toBe('explain')
        ->and($service->detectIntent('Tell me about shotgun formation'))->toBe('explain');
});

it('detects compare intent', function () {
    $service = new AiService;

    expect($service->detectIntent('Cover 2 vs Cover 3'))->toBe('compare')
        ->and($service->detectIntent('What is the difference between 4-3 and 3-4?'))->toBe('compare')
        ->and($service->detectIntent('Compare zone and man coverage'))->toBe('compare');
});

it('detects counter intent', function () {
    $service = new AiService;

    expect($service->detectIntent('How do you beat Cover 2?'))->toBe('counter')
        ->and($service->detectIntent('What stops the zone blitz?'))->toBe('counter')
        ->and($service->detectIntent('What is the weakness of Cover 3?'))->toBe('counter');
});

it('detects pre-snap-read intent', function () {
    $service = new AiService;

    expect($service->detectIntent('What should the QB see here?'))->toBe('pre-snap-read')
        ->and($service->detectIntent('What does this alignment tell you?'))->toBe('pre-snap-read');
});

it('detects recommend intent', function () {
    $service = new AiService;

    expect($service->detectIntent('What should I study next?'))->toBe('recommend')
        ->and($service->detectIntent('Recommend something for beginners'))->toBe('recommend')
        ->and($service->detectIntent('Suggest a concept to learn'))->toBe('recommend');
});

it('defaults to explain for ambiguous queries', function () {
    $service = new AiService;

    expect($service->detectIntent('Cover 2'))->toBe('explain')
        ->and($service->detectIntent('zone blitz'))->toBe('explain');
});

it('builds context from concepts within token cap', function () {
    $service = new AiService;

    $concepts = collect([
        (object) [
            'label' => 'Cover 2',
            'category' => 'coverage',
            'ai_context' => 'Two deep safeties, five underneath zones.',
            'counters' => ['four-verticals'],
            'related' => ['cover-3'],
        ],
        (object) [
            'label' => 'Cover 3',
            'category' => 'coverage',
            'ai_context' => 'Single high safety, four underneath zones.',
            'counters' => ['smash'],
            'related' => ['cover-2'],
        ],
    ]);

    $context = $service->buildContext($concepts);

    expect($context)->toContain('Cover 2 [coverage]')
        ->and($context)->toContain('Two deep safeties')
        ->and($context)->toContain('Counters: four-verticals')
        ->and($context)->toContain('Related: cover-3')
        ->and($context)->toContain('Cover 3 [coverage]');
});

it('truncates context at token cap', function () {
    $service = new AiService;

    // First concept fills most of the 4000 token (~16000 char) budget
    // Second concept should be excluded
    $concepts = collect([
        (object) [
            'label' => 'Concept 1',
            'category' => 'coverage',
            'ai_context' => str_repeat('Detailed. ', 1500), // ~3750 tokens
            'counters' => [],
            'related' => [],
        ],
        (object) [
            'label' => 'Concept 2',
            'category' => 'coverage',
            'ai_context' => str_repeat('More detail. ', 200), // ~650 tokens, would exceed cap
            'counters' => [],
            'related' => [],
        ],
    ]);

    $context = $service->buildContext($concepts);

    expect($context)->toContain('Concept 1')
        ->and($context)->not->toContain('Concept 2');
});

<?php

use App\Models\CustomPlay;
use App\Models\User;

it('creates a custom play via factory', function () {
    $play = CustomPlay::factory()->create();

    expect($play)->toBeInstanceOf(CustomPlay::class)
        ->and($play->id)->toMatch('/^[0-9a-f]{8}-/')
        ->and($play->user)->toBeInstanceOf(User::class);
});

it('casts JSON columns to arrays', function () {
    $play = CustomPlay::factory()->create();

    expect($play->tags)->toBeArray()
        ->and($play->roster)->toBeArray()
        ->and($play->phases)->toBeArray();
});

it('casts is_public to boolean', function () {
    $play = CustomPlay::factory()->create(['is_public' => true]);

    expect($play->is_public)->toBeTrue();
});

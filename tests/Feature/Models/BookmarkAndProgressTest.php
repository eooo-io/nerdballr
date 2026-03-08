<?php

use App\Models\Concept;
use App\Models\User;
use App\Models\UserBookmark;
use App\Models\UserProgress;

it('creates a bookmark with belongs-to relationships', function () {
    $bookmark = UserBookmark::factory()->create();

    expect($bookmark->user)->toBeInstanceOf(User::class)
        ->and($bookmark->concept)->toBeInstanceOf(Concept::class);
});

it('enforces unique bookmark per user-concept pair', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    UserBookmark::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);
    UserBookmark::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);
})->throws(\Illuminate\Database\QueryException::class);

it('allows same concept bookmarked by different users', function () {
    $concept = Concept::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    UserBookmark::factory()->create(['user_id' => $user1->id, 'concept_id' => $concept->id]);
    UserBookmark::factory()->create(['user_id' => $user2->id, 'concept_id' => $concept->id]);

    expect(UserBookmark::count())->toBe(2);
});

it('creates progress with belongs-to relationships', function () {
    $progress = UserProgress::factory()->create();

    expect($progress->user)->toBeInstanceOf(User::class)
        ->and($progress->concept)->toBeInstanceOf(Concept::class)
        ->and($progress->completed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('enforces unique progress per user-concept pair', function () {
    $user = User::factory()->create();
    $concept = Concept::factory()->create();

    UserProgress::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);
    UserProgress::factory()->create(['user_id' => $user->id, 'concept_id' => $concept->id]);
})->throws(\Illuminate\Database\QueryException::class);

it('deleting concept cascades to bookmarks', function () {
    $bookmark = UserBookmark::factory()->create();
    $conceptId = $bookmark->concept_id;

    Concept::find($conceptId)->delete();

    expect(UserBookmark::count())->toBe(0);
});

it('deleting concept cascades to progress', function () {
    $progress = UserProgress::factory()->create();
    $conceptId = $progress->concept_id;

    Concept::find($conceptId)->delete();

    expect(UserProgress::count())->toBe(0);
});

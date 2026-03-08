<?php

namespace App\Http\Controllers;

use App\Http\Requests\MigrateGuestRequest;
use App\Models\AiSession;
use App\Models\Concept;
use Illuminate\Http\JsonResponse;

class GuestMigrationController extends Controller
{
    public function migrate(MigrateGuestRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = [
            'bookmarks_created' => 0,
            'progress_created' => 0,
            'session_transferred' => false,
            'errors' => [],
        ];

        if ($request->has('bookmarks')) {
            $concepts = Concept::whereIn('slug', $request->validated('bookmarks'))->get();
            $foundSlugs = $concepts->pluck('slug')->all();

            foreach ($request->validated('bookmarks') as $slug) {
                if (! in_array($slug, $foundSlugs)) {
                    $result['errors'][] = "Concept not found: {$slug}";
                    continue;
                }
            }

            foreach ($concepts as $concept) {
                $created = $user->bookmarks()->firstOrCreate(
                    ['concept_id' => $concept->id],
                    ['created_at' => now()],
                );
                if ($created->wasRecentlyCreated) {
                    $result['bookmarks_created']++;
                }
            }
        }

        if ($request->has('completed')) {
            $concepts = Concept::whereIn('slug', $request->validated('completed'))->get();
            $foundSlugs = $concepts->pluck('slug')->all();

            foreach ($request->validated('completed') as $slug) {
                if (! in_array($slug, $foundSlugs)) {
                    $result['errors'][] = "Concept not found: {$slug}";
                    continue;
                }
            }

            foreach ($concepts as $concept) {
                $created = $user->progress()->firstOrCreate(
                    ['concept_id' => $concept->id],
                    ['completed_at' => now()],
                );
                if ($created->wasRecentlyCreated) {
                    $result['progress_created']++;
                }
            }
        }

        if ($request->filled('session_key')) {
            $transferred = AiSession::where('session_key', $request->validated('session_key'))
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);

            $result['session_transferred'] = $transferred > 0;
        }

        return response()->json($result);
    }
}

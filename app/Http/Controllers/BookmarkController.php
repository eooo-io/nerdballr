<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Resources\UserBookmarkResource;
use App\Models\UserBookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookmarkController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $bookmarks = $request->user()
            ->bookmarks()
            ->with('concept')
            ->latest('created_at')
            ->paginate(25);

        return UserBookmarkResource::collection($bookmarks);
    }

    public function store(StoreBookmarkRequest $request): UserBookmarkResource
    {
        $bookmark = $request->user()
            ->bookmarks()
            ->firstOrCreate(
                ['concept_id' => $request->validated('concept_id')],
                ['created_at' => now()],
            );

        $bookmark->load('concept');

        return new UserBookmarkResource($bookmark);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $deleted = $request->user()
            ->bookmarks()
            ->where('id', $id)
            ->delete();

        if (! $deleted) {
            abort(404);
        }

        return response()->json(null, 204);
    }
}

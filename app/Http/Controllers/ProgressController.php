<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgressRequest;
use App\Http\Resources\UserProgressResource;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProgressController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $progress = $request->user()
            ->progress()
            ->with('concept')
            ->latest('completed_at')
            ->paginate(25);

        return UserProgressResource::collection($progress);
    }

    public function store(StoreProgressRequest $request): UserProgressResource
    {
        $progress = $request->user()
            ->progress()
            ->firstOrCreate(
                ['concept_id' => $request->validated('concept_id')],
                ['completed_at' => now()],
            );

        $progress->load('concept');

        return new UserProgressResource($progress);
    }
}

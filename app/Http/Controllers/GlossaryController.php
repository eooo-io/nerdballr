<?php

namespace App\Http\Controllers;

use App\Http\Resources\GlossaryTermResource;
use App\Models\GlossaryTerm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GlossaryController extends Controller
{
    private const VALID_CATEGORIES = ['offense', 'defense', 'general', 'scheme'];

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'category' => ['sometimes', 'string', 'in:'.implode(',', self::VALID_CATEGORIES)],
            'q' => ['sometimes', 'string', 'max:100'],
        ]);

        $query = GlossaryTerm::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('q')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('term', 'like', "%{$search}%")
                    ->orWhere('definition', 'like', "%{$search}%");
            });
        }

        $query->orderBy('term');

        return GlossaryTermResource::collection($query->get());
    }

    public function show(string $slug): GlossaryTermResource
    {
        $term = GlossaryTerm::where('slug', $slug)->firstOrFail();

        return new GlossaryTermResource($term);
    }
}

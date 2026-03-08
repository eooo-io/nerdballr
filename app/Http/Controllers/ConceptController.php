<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConceptFullResource;
use App\Http\Resources\ConceptSummaryResource;
use App\Models\Concept;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConceptController extends Controller
{
    private const VALID_CATEGORIES = [
        'formation-offense', 'formation-defense', 'coverage',
        'blitz', 'route-concept', 'pocket-mechanics',
        'ball-physics', 'geometry',
    ];

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'category' => ['sometimes', 'string', 'in:'.implode(',', self::VALID_CATEGORIES)],
            'tags' => ['sometimes', 'string', 'max:200'],
            'q' => ['sometimes', 'string', 'max:100'],
        ]);

        $query = Concept::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('tags')) {
            $tags = array_slice(explode(',', $request->input('tags')), 0, 10);
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        if ($request->filled('q')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return ConceptSummaryResource::collection(
            $query->orderBy('label')->paginate(25)
        );
    }

    public function show(string $slug): ConceptFullResource
    {
        $concept = Concept::where('slug', $slug)->firstOrFail();

        return new ConceptFullResource($concept);
    }
}

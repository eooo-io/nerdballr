<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConceptFullResource;
use App\Http\Resources\ConceptSummaryResource;
use App\Models\Concept;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConceptController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Concept::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('tags')) {
            $tags = explode(',', $request->input('tags'));
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
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

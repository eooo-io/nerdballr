<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Concept */
class ConceptFullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'label' => $this->label,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'tags' => $this->tags,
            'difficulty' => $this->difficulty,
            'layers' => $this->layers,
            'description' => $this->description,
            'explanation' => $this->explanation,
            'roster' => $this->roster,
            'phases' => $this->phases,
            'counters' => $this->counters,
            'related' => $this->related,
            'ai_context' => $this->when($request->user() !== null, $this->ai_context),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

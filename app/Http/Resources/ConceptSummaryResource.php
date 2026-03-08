<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Concept */
class ConceptSummaryResource extends JsonResource
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
        ];
    }
}

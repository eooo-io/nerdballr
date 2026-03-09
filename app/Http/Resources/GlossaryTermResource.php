<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GlossaryTerm */
class GlossaryTermResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'term' => $this->term,
            'slug' => $this->slug,
            'definition' => $this->definition,
            'category' => $this->category,
            'related_terms' => $this->related_terms ?? [],
            'related_concepts' => $this->related_concepts ?? [],
        ];
    }
}

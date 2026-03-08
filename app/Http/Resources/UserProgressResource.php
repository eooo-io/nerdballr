<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserProgress */
class UserProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'concept_slug' => $this->whenLoaded('concept', fn () => $this->concept->slug),
            'concept' => new ConceptSummaryResource($this->whenLoaded('concept')),
            'completed_at' => $this->completed_at,
        ];
    }
}

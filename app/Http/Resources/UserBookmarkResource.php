<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserBookmark */
class UserBookmarkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'concept' => new ConceptSummaryResource($this->whenLoaded('concept')),
            'created_at' => $this->created_at,
        ];
    }
}

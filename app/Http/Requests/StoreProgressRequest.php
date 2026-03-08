<?php

namespace App\Http\Requests;

use App\Models\Concept;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'concept_slug' => ['required', 'string', 'max:255', 'exists:concepts,slug'],
        ];
    }

    public function conceptId(): string
    {
        return Concept::where('slug', $this->validated('concept_slug'))->value('id');
    }
}

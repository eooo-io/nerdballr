<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'max:500'],
            'concept_slugs' => ['required', 'array', 'min:1', 'max:10'],
            'concept_slugs.*' => ['string', 'max:255'],
            'session_key' => ['sometimes', 'string', 'size:64'],
        ];
    }
}

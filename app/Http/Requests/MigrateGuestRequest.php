<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MigrateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bookmarks' => ['sometimes', 'array', 'max:100'],
            'bookmarks.*' => ['string', 'max:255'],
            'completed' => ['sometimes', 'array', 'max:100'],
            'completed.*' => ['string', 'max:255'],
            'session_key' => ['sometimes', 'string', 'max:128'],
        ];
    }
}

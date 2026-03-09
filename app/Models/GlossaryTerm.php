<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlossaryTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'term',
        'slug',
        'definition',
        'category',
        'related_terms',
        'related_concepts',
    ];

    protected function casts(): array
    {
        return [
            'related_terms' => 'array',
            'related_concepts' => 'array',
        ];
    }
}

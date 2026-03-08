<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concept extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'slug',
        'label',
        'category',
        'subcategory',
        'tags',
        'difficulty',
        'layers',
        'description',
        'explanation',
        'roster',
        'phases',
        'counters',
        'related',
        'ai_context',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'layers' => 'array',
            'roster' => 'array',
            'phases' => 'array',
            'counters' => 'array',
            'related' => 'array',
        ];
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(UserBookmark::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}

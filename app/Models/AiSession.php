<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'session_key',
        'messages',
        'concept_ids',
    ];

    protected function casts(): array
    {
        return [
            'messages' => 'array',
            'concept_ids' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'form_definition_id',
        'data',
        'ip',
        'user_agent',
        'is_spam',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_spam' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function formDefinition(): BelongsTo
    {
        return $this->belongsTo(FormDefinition::class);
    }

    public function isSpam(): bool
    {
        return $this->is_spam;
    }

    public function markAsSpam(): void
    {
        $this->is_spam = true;
        $this->save();
    }
}

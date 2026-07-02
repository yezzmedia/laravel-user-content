<?php

declare(strict_types=1);

namespace YezzMedia\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YezzMedia\UserProjects\Models\Project;

class FormDefinition extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'fields',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'options' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeForProject(Builder $query, int $projectId): void
    {
        $query->where('project_id', $projectId);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class, 'form_definition_id');
    }

    public function getFieldList(): array
    {
        return $this->fields ?? [];
    }

    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    public function isHoneypotEnabled(): bool
    {
        return (bool) $this->getOption('honeypot', true);
    }

    public function getRateLimit(): int
    {
        return (int) $this->getOption('rate_limit', 5);
    }

    public function getRateLimitPeriod(): int
    {
        return (int) $this->getOption('rate_limit_period', 60);
    }

    public function getNotifyEmail(): ?string
    {
        return $this->getOption('notify_email');
    }
}

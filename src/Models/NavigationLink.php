<?php

declare(strict_types=1);

namespace YezzMedia\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YezzMedia\UserProjects\Models\Project;

class NavigationLink extends Model
{
    protected $fillable = [
        'project_id',
        'label',
        'url',
        'page_id',
        'parent_id',
        'section',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function resolvedUrl(): string
    {
        if ($this->page_id !== null && $this->page !== null) {
            return $this->page->getNavigationUrl();
        }

        return $this->url ?? '#';
    }

    public function resolvedLabel(): string
    {
        if ($this->label !== null) {
            return $this->label;
        }

        return $this->page?->title ?? 'Untitled';
    }

    public function scopeForProject(Builder $query, int $projectId): void
    {
        $query->where('project_id', $projectId);
    }

    public function isValid(): bool
    {
        return $this->page_id !== null || ($this->url !== null && $this->url !== '');
    }
}

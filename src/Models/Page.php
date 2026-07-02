<?php

declare(strict_types=1);

namespace YezzMedia\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use YezzMedia\Content\Enums\PageStatus;
use YezzMedia\UserProjects\Models\Project;

class Page extends Model
{
    use HasSlug;

    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'content',
        'status',
        'show_in_navigation',
        'sort_order',
        'parent_id',
        'seo_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PageStatus::class,
            'show_in_navigation' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', PageStatus::Published)
            ->whereNotNull('published_at');
    }

    public function scopeDraft(Builder $query): void
    {
        $query->where('status', PageStatus::Draft);
    }

    public function scopeInNavigation(Builder $query): void
    {
        $query->published()
            ->where('show_in_navigation', true)
            ->orderBy('sort_order');
    }

    public function scopeForProject(Builder $query, int $projectId): void
    {
        $query->where('project_id', $projectId);
    }

    public function scopeBySlug(Builder $query, string $slug): void
    {
        $query->where('slug', $slug);
    }

    public static function findBySlug(string $slug, ?int $projectId = null): ?self
    {
        $query = static::query()->bySlug($slug);

        if ($projectId !== null) {
            $query->forProject($projectId);
        }

        return $query->first();
    }

    public function isPublished(): bool
    {
        return $this->status === PageStatus::Published && $this->published_at !== null;
    }

    public function isDraft(): bool
    {
        return $this->status === PageStatus::Draft;
    }

    public function publish(): void
    {
        $this->status = PageStatus::Published;
        $this->published_at = now();
        $this->save();
    }

    public function unpublish(): void
    {
        $this->status = PageStatus::Draft;
        $this->published_at = null;
        $this->save();
    }

    public function getNavigationUrl(): string
    {
        return '/'.($this->project?->slug ?? 'project').'/'.$this->slug;
    }

    public function getBreadcrumbLabel(): string
    {
        return $this->title;
    }
}

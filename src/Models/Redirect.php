<?php

declare(strict_types=1);

namespace YezzMedia\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YezzMedia\UserProjects\Models\Project;

class Redirect extends Model
{
    protected $fillable = [
        'project_id',
        'source',
        'target',
        'status_code',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'enabled' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enabled', true);
    }

    public function scopeForProject(Builder $query, int $projectId): void
    {
        $query->where('project_id', $projectId);
    }

    public static function findBySource(string $source, int $projectId): ?self
    {
        return static::query()
            ->enabled()
            ->forProject($projectId)
            ->where('source', $source)
            ->first();
    }
}

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use Illuminate\Support\Collection;
use YezzMedia\Content\Models\Page;

class PageService
{
    public function getPublishedPages(int $projectId): Collection
    {
        return Page::query()
            ->published()
            ->forProject($projectId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getNavigationPages(int $projectId): Collection
    {
        return Page::query()
            ->inNavigation()
            ->forProject($projectId)
            ->with('children')
            ->get();
    }

    public function findPublishedBySlug(string $slug, int $projectId): ?Page
    {
        return Page::query()
            ->published()
            ->forProject($projectId)
            ->bySlug($slug)
            ->first();
    }

    public function getPageTree(int $projectId, ?int $parentId = null): Collection
    {
        return Page::query()
            ->forProject($projectId)
            ->where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->with('children')
            ->get();
    }
}

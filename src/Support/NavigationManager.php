<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use YezzMedia\Content\Models\NavigationLink;
use YezzMedia\Content\Models\Page;
use YezzMedia\UserProjects\Models\Project;

class NavigationManager
{
    public function __construct(private readonly Request $request) {}

    public function buildMenu(Project $project, string $section = 'header'): array
    {
        $pages = $this->getNavigationPages($project->id);
        $links = $this->getNavigationLinks($project->id, $section);

        $items = $this->mergeItems($pages, $links);
        $items = $this->sortItems($items);

        return $this->buildTree($items);
    }

    public function isActive(string $url): bool
    {
        $currentPath = $this->request->path();

        return $currentPath === trim($url, '/')
            || str_starts_with($currentPath, trim($url, '/').'/');
    }

    private function getNavigationPages(int $projectId): Collection
    {
        return Page::query()
            ->inNavigation()
            ->forProject($projectId)
            ->get();
    }

    private function getNavigationLinks(int $projectId, string $section): Collection
    {
        return NavigationLink::query()
            ->forProject($projectId)
            ->where('section', $section)
            ->with('page')
            ->get()
            ->filter(fn (NavigationLink $link) => $link->isValid());
    }

    private function mergeItems(Collection $pages, Collection $links): Collection
    {
        $menuNodes = collect();

        foreach ($pages as $page) {
            $menuNodes->push([
                'id' => 'page_'.$page->id,
                'label' => $page->title,
                'url' => $page->getNavigationUrl(),
                'parent_id' => null,
                'sort_order' => $page->sort_order,
                'is_active' => $this->isActive($page->getNavigationUrl()),
                'type' => 'page',
            ]);
        }

        foreach ($links as $link) {
            $menuNodes->push([
                'id' => 'link_'.$link->id,
                'label' => $link->resolvedLabel(),
                'url' => $link->resolvedUrl(),
                'parent_id' => $link->parent_id ? 'link_'.$link->parent_id : null,
                'sort_order' => $link->sort_order,
                'is_active' => $this->isActive($link->resolvedUrl()),
                'type' => 'link',
            ]);
        }

        return $menuNodes;
    }

    private function sortItems(Collection $items): Collection
    {
        return $items->sortBy('sort_order')->values();
    }

    private function buildTree(Collection $items): array
    {
        $tree = [];
        $children = [];

        foreach ($items as $item) {
            $item['children'] = [];

            if ($item['parent_id'] === null) {
                $tree[] = $item;
            } else {
                $children[$item['parent_id']][] = $item;
            }
        }

        foreach ($tree as &$node) {
            if (isset($children[$node['id']])) {
                $node['children'] = $children[$node['id']];
            }
        }

        return $tree;
    }
}

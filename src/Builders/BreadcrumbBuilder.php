<?php

declare(strict_types=1);

namespace YezzMedia\Content\Builders;

use YezzMedia\Content\Models\Page;
use YezzMedia\UserProjects\Models\Project;

class BreadcrumbBuilder
{
    public function build(Project $project, ?Page $currentPage): array
    {
        $crumbs = [
            ['label' => $project->name, 'url' => '/hub/'.$project->id],
        ];

        if ($currentPage === null) {
            return $crumbs;
        }

        $chain = $this->buildParentChain($currentPage);

        foreach ($chain as $page) {
            $crumbs[] = [
                'label' => $page->title,
                'url' => $page->getNavigationUrl(),
            ];
        }

        return $crumbs;
    }

    private function buildParentChain(Page $page): array
    {
        $chain = [];
        $current = $page;

        while ($current !== null) {
            $chain[] = $current;
            $current = $current->parent;
        }

        return array_reverse($chain);
    }
}

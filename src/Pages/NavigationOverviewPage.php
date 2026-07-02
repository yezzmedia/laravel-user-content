<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Content\Models\NavigationLink;

class NavigationOverviewPage extends ContentBasePage
{
    protected static ?string $slug = 'content/navigation';

    protected function getPageTitle(): string
    {
        return 'Navigation';
    }

    protected function getPageDescription(): string
    {
        return 'Manage header and footer menu links for your project website.';
    }

    protected function pageData(): array
    {
        $projectId = request()->query('project');

        if ($projectId === null) {
            return ['links' => []];
        }

        return [
            'links' => NavigationLink::query()
                ->forProject((int) $projectId)
                ->orderBy('section')
                ->orderBy('sort_order')
                ->get(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Content\Models\Page;

class PagesOverviewPage extends ContentBasePage
{
    protected static ?string $slug = 'content/pages';

    protected function getPageTitle(): string
    {
        return 'Pages';
    }

    protected function getPageDescription(): string
    {
        return 'Manage website pages, content, and publication status.';
    }

    protected function pageData(): array
    {
        $projectId = request()->query('project');

        if ($projectId === null) {
            return ['pages' => []];
        }

        return [
            'pages' => Page::query()
                ->forProject((int) $projectId)
                ->orderBy('sort_order')
                ->get(),
        ];
    }
}

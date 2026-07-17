<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Content\Models\Redirect;

class RedirectsOverviewPage extends ContentBasePage
{
    protected string $view = 'user-content::pages.redirects-overview';

    protected static ?string $slug = 'content/redirects';

    protected function getPageTitle(): string
    {
        return 'Redirects';
    }

    protected function getPageDescription(): string
    {
        return 'Manage URL redirect rules.';
    }

    protected function pageData(): array
    {
        if ($this->projectId === null) {
            return ['redirects' => []];
        }

        return [
            'redirects' => Redirect::query()
                ->forProject($this->projectId)
                ->orderBy('source')
                ->get(),
        ];
    }
}

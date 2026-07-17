<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Content\Models\FormDefinition;

class FormsOverviewPage extends ContentBasePage
{
    protected string $view = 'user-content::pages.forms-overview';

    protected static ?string $slug = 'content/forms';

    protected function getPageTitle(): string
    {
        return 'Forms';
    }

    protected function getPageDescription(): string
    {
        return 'Manage form definitions and view submissions.';
    }

    protected function pageData(): array
    {
        if ($this->projectId === null) {
            return ['forms' => []];
        }

        return [
            'forms' => FormDefinition::query()
                ->forProject($this->projectId)
                ->withCount('submissions')
                ->get(),
        ];
    }
}

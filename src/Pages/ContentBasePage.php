<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Dashboard\Pages\DashboardPage;

abstract class ContentBasePage extends DashboardPage
{
    protected static bool $shouldRegisterNavigation = true;

    public static function canAccess(): bool
    {
        return auth(config('user-projects.panel.guard', 'web'))->check();
    }

    abstract protected function getPageTitle(): string;

    abstract protected function getPageDescription(): string;

    abstract protected function pageData(): array;

    protected function getViewData(): array
    {
        return [
            'pageTitle' => $this->getPageTitle(),
            'pageData' => $this->pageData(),
            'pageDescription' => $this->getPageDescription(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getPageTitle();
    }
}

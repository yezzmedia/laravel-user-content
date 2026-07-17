<?php

declare(strict_types=1);

namespace YezzMedia\Content\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use YezzMedia\Content\Pages\FormsOverviewPage;
use YezzMedia\Content\Pages\NavigationOverviewPage;
use YezzMedia\Content\Pages\PagesOverviewPage;
use YezzMedia\Content\Pages\RedirectsOverviewPage;

final class ContentPlugin implements Plugin
{
    public function getId(): string
    {
        return 'user-content';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            PagesOverviewPage::class,
            NavigationOverviewPage::class,
            RedirectsOverviewPage::class,
            FormsOverviewPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}

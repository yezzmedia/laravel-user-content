<?php

declare(strict_types=1);

namespace YezzMedia\Content\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class ContentPlugin implements Plugin
{
    public function getId(): string
    {
        return 'user-content';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            \YezzMedia\Content\Pages\PagesOverviewPage::class,
            \YezzMedia\Content\Pages\NavigationOverviewPage::class,
            \YezzMedia\Content\Pages\RedirectsOverviewPage::class,
            \YezzMedia\Content\Pages\FormsOverviewPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}

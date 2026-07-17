<?php

declare(strict_types=1);

namespace YezzMedia\Content;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YezzMedia\Content\Events\PagePublished;
use YezzMedia\Content\Events\PageSlugChanged;
use YezzMedia\Content\Events\PageUnpublished;
use YezzMedia\Content\Filament\ContentPlugin;
use YezzMedia\Content\Http\Controllers\PageController;
use YezzMedia\Content\Listeners\ContentAuditListener;
use YezzMedia\Content\Listeners\CreateRedirectOnSlugChange;
use YezzMedia\Content\Routing\PageUrlResolver;
use YezzMedia\Content\Support\ContentAddonRegistrar;
use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Content\Support\FormService;
use YezzMedia\Content\Support\NavigationManager;
use YezzMedia\Content\Support\PageService;
use YezzMedia\Content\Support\RedirectManager;
use YezzMedia\Dashboard\Support\HubExtensionRegistry;
use YezzMedia\Foundation\Support\PlatformPackageRegistrar;
use YezzMedia\UserProjects\Support\InstalledAddonRegistry;
use YezzMedia\UserProjects\Support\ProjectAddonManager;

class ContentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-user-content')
            ->hasConfigFile('user-content')
            ->hasMigrations([
                '0001_create_pages_table',
                '0002_create_navigation_links_table',
                '0003_create_redirects_table',
                '0004_create_form_definitions_table',
                '0005_create_form_submissions_table',
            ])
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(PageService::class);
        $this->app->singleton(NavigationManager::class);
        $this->app->singleton(RedirectManager::class);
        $this->app->singleton(FormService::class);
        $this->app->singleton(ContentStoreSetup::class);
        $this->app->singleton(PageUrlResolver::class);
        $this->app->singleton(PageController::class);

        if (class_exists(HubExtensionRegistry::class)) {
            $this->app->make(HubExtensionRegistry::class)
                ->register(ContentPlugin::class);
        }
    }

    public function packageBooted(): void
    {
        $this->app->make(PlatformPackageRegistrar::class)
            ->register(new ContentPlatformPackage);

        $this->registerEventListeners($this->app->make(Dispatcher::class));
        $this->registerProjectAddons();
        $this->registerInstalledAddons();
        $this->registerFrontendRoutes();
    }

    private function registerProjectAddons(): void
    {
        if (! class_exists(ProjectAddonManager::class)) {
            return;
        }

        $manager = $this->app->make(ProjectAddonManager::class);

        (new ContentAddonRegistrar)->register($manager);
    }

    private function registerEventListeners(Dispatcher $events): void
    {
        $events->listen(
            PagePublished::class,
            [ContentAuditListener::class, 'handlePagePublished'],
        );

        $events->listen(
            PageUnpublished::class,
            [ContentAuditListener::class, 'handlePageUnpublished'],
        );

        $events->listen(
            PageSlugChanged::class,
            [CreateRedirectOnSlugChange::class, 'handle'],
        );

        $events->listen(
            PageSlugChanged::class,
            [ContentAuditListener::class, 'handlePageSlugChanged'],
        );
    }

    private function registerInstalledAddons(): void
    {
        if (! class_exists(InstalledAddonRegistry::class)) {
            return;
        }

        try {
            $registry = $this->app->make(InstalledAddonRegistry::class);
            $registry->register('content', 'Content', '1.0.0', 'Manage pages, navigation links, redirects, and forms.');
        } catch (\Throwable) {
            // Silently skip when the installed_addons table does not exist yet.
        }
    }

    private function registerFrontendRoutes(): void
    {
        $this->app->booted(function (): void {
            Route::fallback([PageController::class, 'show']);
        });
    }
}

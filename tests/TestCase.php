<?php

declare(strict_types=1);

namespace Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Spatie\Honeypot\SpamProtectionServiceProvider;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Sluggable\SluggableServiceProvider;
use YezzMedia\Content\ContentServiceProvider;
use YezzMedia\Dashboard\DashboardServiceProvider;
use YezzMedia\Foundation\FoundationServiceProvider;
use YezzMedia\Foundation\Testing\FoundationTestCase;
use YezzMedia\UserProjects\UserProjectsServiceProvider;

abstract class TestCase extends FoundationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $app['config']->set('user-content.cache.enabled', false);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            NotificationsServiceProvider::class,
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            SluggableServiceProvider::class,
            SpamProtectionServiceProvider::class,
            FoundationServiceProvider::class,
            DashboardServiceProvider::class,
            UserProjectsServiceProvider::class,
            ContentServiceProvider::class,
        ];
    }
}

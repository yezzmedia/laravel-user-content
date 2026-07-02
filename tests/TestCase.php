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
use Illuminate\Contracts\Auth\Authenticatable;
use Livewire\LivewireServiceProvider;
use Spatie\Honeypot\HoneypotServiceProvider;
use YezzMedia\Content\ContentServiceProvider;
use YezzMedia\Dashboard\DashboardServiceProvider;
use YezzMedia\Foundation\FoundationServiceProvider;
use YezzMedia\Foundation\Testing\FoundationTestCase;
use YezzMedia\UserProjects\Models\Project;
use YezzMedia\UserProjects\Models\ProjectMember;
use YezzMedia\UserProjects\UserProjectsServiceProvider;

abstract class TestCase extends FoundationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('app.env', 'local');
        $app['config']->set('auth.providers.users.model', TestUser::class);
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
        $this->loadMigrationsFrom(dirname(__DIR__).'/../laravel-user-projects/database/migrations');
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
            HoneypotServiceProvider::class,
            FoundationServiceProvider::class,
            DashboardServiceProvider::class,
            UserProjectsServiceProvider::class,
            ContentServiceProvider::class,
        ];
    }

    protected function createUser(): Authenticatable
    {
        $userClass = config('auth.providers.users.model');

        $user = $userClass::forceCreate([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'web');

        return $user;
    }

    protected function createProject(): Project
    {
        $user = $this->createUser();

        return Project::query()->create([
            'owner_id' => $user->getAuthIdentifier(),
            'name' => 'Test Project',
            'description' => 'A test project',
            'status' => 'active',
        ]);
    }
}

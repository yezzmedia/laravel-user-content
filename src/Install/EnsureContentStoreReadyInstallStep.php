<?php

declare(strict_types=1);

namespace YezzMedia\Content\Install;

use Illuminate\Support\Facades\Artisan;
use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Install\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class EnsureContentStoreReadyInstallStep implements InstallStep
{
    public function __construct(private readonly ContentStoreSetup $setup) {}

    public function key(): string
    {
        return 'ensure_content_store_ready';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-content';
    }

    public function priority(): int
    {
        return 20;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return ! $this->setup->allTablesExist();
    }

    public function handle(InstallContext $context): void
    {
        if ($context->migrationsAllowed) {
            Artisan::call('migrate', ['--force' => true]);
        }

        if (! $this->setup->allTablesExist()) {
            throw new \RuntimeException(
                'Content tables could not be created after running migrations.',
            );
        }
    }
}

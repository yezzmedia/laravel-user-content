<?php

declare(strict_types=1);

namespace YezzMedia\Content\Install;

use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Install\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class PublishContentMigrationsInstallStep implements InstallStep
{
    public function __construct(private readonly ContentStoreSetup $setup) {}

    public function key(): string
    {
        return 'publish_content_migrations';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-content';
    }

    public function priority(): int
    {
        return 10;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return $context->refreshPublishedResources || ! $this->setup->allTablesExist();
    }

    public function handle(InstallContext $context): void
    {
        $this->publishMigrations();
    }

    private function publishMigrations(): void
    {
        $source = __DIR__.'/../../database/migrations';
        $target = database_path('migrations');

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        foreach (glob($source.'/*.php') as $file) {
            $filename = basename($file);
            $targetPath = $target.'/'.$filename;

            if (! file_exists($targetPath)) {
                copy($file, $targetPath);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Install;

use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Install\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class PublishContentConfigInstallStep implements InstallStep
{
    public function __construct(private readonly ContentStoreSetup $setup) {}

    public function key(): string
    {
        return 'publish_content_config';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-content';
    }

    public function priority(): int
    {
        return 30;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return $context->refreshPublishedResources || ! $this->setup->configPublished();
    }

    public function handle(InstallContext $context): void
    {
        $this->setup->publishConfig($context->refreshPublishedResources);
    }
}

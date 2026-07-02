<?php

declare(strict_types=1);

use YezzMedia\Content\ContentPlatformPackage;
use YezzMedia\Content\Install\EnsureContentStoreReadyInstallStep;
use YezzMedia\Content\Install\PublishContentConfigInstallStep;
use YezzMedia\Content\Install\PublishContentMigrationsInstallStep;
use YezzMedia\Foundation\Install\InstallContext;

it('has correct keys on install steps', function () {
    $steps = (new ContentPlatformPackage)->installSteps();

    expect($steps[0]->key())->toBe('publish_content_migrations')
        ->and($steps[1]->key())->toBe('ensure_content_store_ready')
        ->and($steps[2]->key())->toBe('publish_content_config');
});

it('has install steps with correct package', function () {
    $steps = (new ContentPlatformPackage)->installSteps();

    foreach ($steps as $step) {
        expect($step->package())->toBe('yezzmedia/laravel-user-content');
    }
});

it('publish migrations step has correct priority', function () {
    $step = app(PublishContentMigrationsInstallStep::class);

    expect($step->priority())->toBe(10);
});

it('ensure store ready step has correct priority', function () {
    $step = app(EnsureContentStoreReadyInstallStep::class);

    expect($step->priority())->toBe(20);
});

it('publish config step has correct priority', function () {
    $step = app(PublishContentConfigInstallStep::class);

    expect($step->priority())->toBe(30);
});

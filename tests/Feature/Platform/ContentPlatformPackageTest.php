<?php

declare(strict_types=1);

use YezzMedia\Content\ContentPlatformPackage;
use YezzMedia\Foundation\Contracts\DefinesAuditEvents;
use YezzMedia\Foundation\Contracts\DefinesInstallSteps;
use YezzMedia\Foundation\Contracts\DefinesPermissions;
use YezzMedia\Foundation\Contracts\DefinesRateLimiters;
use YezzMedia\Foundation\Contracts\PlatformPackage;
use YezzMedia\Foundation\Contracts\ProvidesDoctorChecks;
use YezzMedia\Foundation\Contracts\RegistersFeatures;

it('implements all required contracts', function () {
    $pkg = new ContentPlatformPackage;

    expect($pkg)->toBeInstanceOf(PlatformPackage::class)
        ->toBeInstanceOf(DefinesPermissions::class)
        ->toBeInstanceOf(DefinesInstallSteps::class)
        ->toBeInstanceOf(DefinesAuditEvents::class)
        ->toBeInstanceOf(DefinesRateLimiters::class)
        ->toBeInstanceOf(ProvidesDoctorChecks::class)
        ->toBeInstanceOf(RegistersFeatures::class);
});

it('provides correct metadata', function () {
    $pkg = new ContentPlatformPackage;

    $metadata = $pkg->metadata();

    expect($metadata->name)->toBe('yezzmedia/laravel-user-content')
        ->and($metadata->vendor)->toBe('yezzmedia');
});

it('defines 9 permissions', function () {
    $permissions = (new ContentPlatformPackage)->permissionDefinitions();

    expect($permissions)->toHaveCount(9);
});

it('defines 4 features', function () {
    $features = (new ContentPlatformPackage)->featureDefinitions();

    expect($features)->toHaveCount(4);
});

it('defines 8 audit events', function () {
    $events = (new ContentPlatformPackage)->auditEventDefinitions();

    expect($events)->toHaveCount(8);
});

it('defines 1 rate limiter', function () {
    $rateLimiters = (new ContentPlatformPackage)->rateLimitDefinitions();

    expect($rateLimiters)->toHaveCount(1);
});

it('defines 3 install steps', function () {
    $steps = (new ContentPlatformPackage)->installSteps();

    expect($steps)->toHaveCount(3);
});

it('defines 3 doctor checks', function () {
    $checks = (new ContentPlatformPackage)->doctorChecks();

    expect($checks)->toHaveCount(3);
});

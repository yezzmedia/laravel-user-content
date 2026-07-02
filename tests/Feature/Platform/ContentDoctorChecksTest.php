<?php

declare(strict_types=1);

use YezzMedia\Content\Doctor\ContentStoreReadyCheck;
use YezzMedia\Content\Doctor\OrphanedRedirectsCheck;
use YezzMedia\Content\Doctor\PendingFormSubmissionsCheck;

it('content store ready check has correct key', function () {
    $check = app(ContentStoreReadyCheck::class);

    expect($check->key())->toBe('content_store_ready')
        ->and($check->package())->toBe('yezzmedia/laravel-user-content');
});

it('orphaned redirects check has correct key', function () {
    $check = app(OrphanedRedirectsCheck::class);

    expect($check->key())->toBe('content_orphaned_redirects')
        ->and($check->package())->toBe('yezzmedia/laravel-user-content');
});

it('pending form submissions check has correct key', function () {
    $check = app(PendingFormSubmissionsCheck::class);

    expect($check->key())->toBe('content_pending_form_submissions')
        ->and($check->package())->toBe('yezzmedia/laravel-user-content');
});

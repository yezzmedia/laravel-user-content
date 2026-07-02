<?php

declare(strict_types=1);

use YezzMedia\Content\ContentPlatformPackage;

it('defines content.pages.view permission', function () {
    $perms = (new ContentPlatformPackage)->permissionDefinitions();
    $names = array_map(fn ($p) => $p->name, $perms);

    expect($names)->toContain('content.pages.view')
        ->toContain('content.pages.create')
        ->toContain('content.pages.update')
        ->toContain('content.pages.publish')
        ->toContain('content.navigation.manage')
        ->toContain('content.redirects.manage')
        ->toContain('content.forms.manage')
        ->toContain('content.forms.submissions.view')
        ->toContain('content.forms.submissions.export');
});

it('all permissions belong to correct package', function () {
    $perms = (new ContentPlatformPackage)->permissionDefinitions();

    foreach ($perms as $perm) {
        expect($perm->package)->toBe('yezzmedia/laravel-user-content');
    }
});

it('all permissions have labels and descriptions', function () {
    $perms = (new ContentPlatformPackage)->permissionDefinitions();

    foreach ($perms as $perm) {
        expect($perm->label)->not->toBeEmpty()
            ->and($perm->description)->not->toBeEmpty();
    }
});

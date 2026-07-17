<?php

declare(strict_types=1);

use YezzMedia\Content\Events\PageSlugChanged;
use YezzMedia\Content\Listeners\CreateRedirectOnSlugChange;
use YezzMedia\Content\Models\Redirect;

it('creates redirect on slug change', function () {
    $project = $this->createProject();
    $event = new PageSlugChanged(
        projectId: $project->id,
        pageId: 1,
        oldSlug: 'old-page-slug',
        newSlug: 'new-page-slug',
    );

    app(CreateRedirectOnSlugChange::class)->handle($event);

    $redirect = Redirect::findBySource('/old-page-slug', $project->id);
    expect($redirect)->not->toBeNull()
        ->and($redirect->target)->toBe('/new-page-slug')
        ->and($redirect->status_code)->toBe(301)
        ->and($redirect->enabled)->toBeTrue();
});

it('updates existing redirect when slug changes again', function () {
    $project = $this->createProject();

    Redirect::create([
        'project_id' => $project->id,
        'source' => '/original',
        'target' => '/first-change',
        'enabled' => false,
    ]);

    $event = new PageSlugChanged(
        projectId: $project->id,
        pageId: 1,
        oldSlug: 'original',
        newSlug: 'second-change',
    );

    app(CreateRedirectOnSlugChange::class)->handle($event);

    $redirect = Redirect::findBySource('/original', $project->id);
    expect($redirect)->not->toBeNull()
        ->and($redirect->target)->toBe('/second-change')
        ->and($redirect->enabled)->toBeTrue();
});

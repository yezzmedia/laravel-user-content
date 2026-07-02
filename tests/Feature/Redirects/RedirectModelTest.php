<?php

declare(strict_types=1);

use YezzMedia\Content\Models\Redirect;
use YezzMedia\UserProjects\Models\Project;

it('creates a redirect', function () {
    $project = Project::factory()->create();

    $redirect = Redirect::create([
        'project_id' => $project->id,
        'source' => '/old-page',
        'target' => '/new-page',
        'status_code' => 301,
    ]);

    expect($redirect->source)->toBe('/old-page')
        ->and($redirect->target)->toBe('/new-page')
        ->and($redirect->status_code)->toBe(301)
        ->and($redirect->enabled)->toBeTrue();
});

it('scopes enabled redirects', function () {
    $project = Project::factory()->create();

    Redirect::create(['project_id' => $project->id, 'source' => '/a', 'target' => '/b', 'enabled' => true]);
    Redirect::create(['project_id' => $project->id, 'source' => '/c', 'target' => '/d', 'enabled' => false]);

    expect(Redirect::enabled()->count())->toBe(1);
});

it('finds by source within project', function () {
    $project = Project::factory()->create();
    Redirect::create(['project_id' => $project->id, 'source' => '/old', 'target' => '/new']);

    $found = Redirect::findBySource('/old', $project->id);
    expect($found)->not->toBeNull()
        ->and($found->target)->toBe('/new');
});

it('respects project isolation for find by source', function () {
    $projectA = Project::factory()->create();
    $projectB = Project::factory()->create();

    Redirect::create(['project_id' => $projectA->id, 'source' => '/page', 'target' => '/page-a']);
    Redirect::create(['project_id' => $projectB->id, 'source' => '/page', 'target' => '/page-b']);

    $found = Redirect::findBySource('/page', $projectA->id);
    expect($found->target)->toBe('/page-a');
});

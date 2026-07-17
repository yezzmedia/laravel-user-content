<?php

declare(strict_types=1);

use YezzMedia\Content\Builders\BreadcrumbBuilder;
use YezzMedia\Content\Models\Page;

it('builds breadcrumbs for root page', function () {
    $project = $this->createProject();
    $page = Page::create(['project_id' => $project->id, 'title' => 'About']);

    $crumbs = app(BreadcrumbBuilder::class)->build($project, $page);

    expect($crumbs)->toHaveCount(2)
        ->and($crumbs[0]['label'])->toBe($project->name)
        ->and($crumbs[1]['label'])->toBe('About');
});

it('builds breadcrumbs for child page', function () {
    $project = $this->createProject();
    $parent = Page::create(['project_id' => $project->id, 'title' => 'Services']);
    $child = Page::create(['project_id' => $project->id, 'title' => 'Web Design', 'parent_id' => $parent->id]);

    $crumbs = app(BreadcrumbBuilder::class)->build($project, $child);

    expect($crumbs)->toHaveCount(3)
        ->and($crumbs[0]['label'])->toBe($project->name)
        ->and($crumbs[1]['label'])->toBe('Services')
        ->and($crumbs[2]['label'])->toBe('Web Design');
});

it('returns only project when no current page', function () {
    $project = $this->createProject();

    $crumbs = app(BreadcrumbBuilder::class)->build($project, null);

    expect($crumbs)->toHaveCount(1)
        ->and($crumbs[0]['label'])->toBe($project->name);
});

<?php

declare(strict_types=1);

use YezzMedia\Content\Models\NavigationLink;
use YezzMedia\Content\Models\Page;
use YezzMedia\Content\Support\NavigationManager;

it('builds menu from pages and links', function () {
    $project = $this->createProject();

    $page = Page::create(['project_id' => $project->id, 'title' => 'Home']);
    $page->publish();

    NavigationLink::create([
        'project_id' => $project->id,
        'label' => 'External',
        'url' => 'https://example.com',
        'section' => 'header',
        'sort_order' => 1,
    ]);

    $menu = app(NavigationManager::class)->buildMenu($project, 'header');

    expect($menu)->toHaveCount(2);
});

it('filters by section', function () {
    $project = $this->createProject();

    NavigationLink::create([
        'project_id' => $project->id,
        'label' => 'Header Link',
        'url' => '/header',
        'section' => 'header',
    ]);

    NavigationLink::create([
        'project_id' => $project->id,
        'label' => 'Footer Link',
        'url' => '/footer',
        'section' => 'footer',
    ]);

    $header = app(NavigationManager::class)->buildMenu($project, 'header');
    $footer = app(NavigationManager::class)->buildMenu($project, 'footer');

    expect($header)->toHaveCount(1)
        ->and($footer)->toHaveCount(1);
});

it('sorts items by sort_order', function () {
    $project = $this->createProject();

    NavigationLink::create(['project_id' => $project->id, 'label' => 'Second', 'url' => '/second', 'sort_order' => 2]);
    NavigationLink::create(['project_id' => $project->id, 'label' => 'First', 'url' => '/first', 'sort_order' => 1]);

    $menu = app(NavigationManager::class)->buildMenu($project, 'header');

    expect($menu[0]['label'])->toBe('First')
        ->and($menu[1]['label'])->toBe('Second');
});

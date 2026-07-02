<?php

declare(strict_types=1);

use YezzMedia\Content\Enums\PageStatus;
use YezzMedia\Content\Models\Page;
use YezzMedia\UserProjects\Models\Project;

it('creates a page', function () {
    $project = Project::factory()->create();

    $page = Page::create([
        'project_id' => $project->id,
        'title' => 'Test Page',
        'content' => 'Hello World',
    ]);

    expect($page->title)->toBe('Test Page')
        ->and($page->slug)->toBe('test-page')
        ->and($page->status)->toBe(PageStatus::Draft)
        ->and($page->project_id)->toBe($project->id);
});

it('generates unique slug per project', function () {
    $project = Project::factory()->create();
    $other = Project::factory()->create();

    Page::create(['project_id' => $project->id, 'title' => 'Same Title']);
    Page::create(['project_id' => $other->id, 'title' => 'Same Title']);

    $pages = Page::all();
    expect($pages)->toHaveCount(2);
    expect($pages[0]->slug)->toBe('same-title');
    expect($pages[1]->slug)->toBe('same-title');
});

it('scopes pages by project', function () {
    $projectA = Project::factory()->create();
    $projectB = Project::factory()->create();

    Page::create(['project_id' => $projectA->id, 'title' => 'Page A']);
    Page::create(['project_id' => $projectB->id, 'title' => 'Page B']);

    expect(Page::forProject($projectA->id)->count())->toBe(1)
        ->and(Page::forProject($projectB->id)->count())->toBe(1);
});

it('scopes published pages', function () {
    $project = Project::factory()->create();

    $draft = Page::create(['project_id' => $project->id, 'title' => 'Draft']);
    $published = Page::create(['project_id' => $project->id, 'title' => 'Published']);
    $published->publish();

    expect(Page::published()->count())->toBe(1)
        ->and(Page::draft()->count())->toBe(1);
});

it('scopes pages in navigation', function () {
    $project = Project::factory()->create();

    $visible = Page::create(['project_id' => $project->id, 'title' => 'Visible', 'show_in_navigation' => true]);
    $visible->publish();
    $hidden = Page::create(['project_id' => $project->id, 'title' => 'Hidden', 'show_in_navigation' => false]);
    $hidden->publish();

    expect(Page::inNavigation()->count())->toBe(1);
});

it('publishes and unpublishes', function () {
    $project = Project::factory()->create();
    $page = Page::create(['project_id' => $project->id, 'title' => 'Test']);

    expect($page->isDraft())->toBeTrue();

    $page->publish();
    expect($page->isPublished())->toBeTrue()
        ->and($page->published_at)->not->toBeNull();

    $page->unpublish();
    expect($page->isDraft())->toBeTrue()
        ->and($page->published_at)->toBeNull();
});

it('finds page by slug', function () {
    $project = Project::factory()->create();
    Page::create(['project_id' => $project->id, 'title' => 'My Page']);

    $found = Page::findBySlug('my-page', $project->id);
    expect($found)->not->toBeNull()
        ->and($found->title)->toBe('My Page');
});

it('handles parent-child relationships', function () {
    $project = Project::factory()->create();
    $parent = Page::create(['project_id' => $project->id, 'title' => 'Parent']);
    $child = Page::create(['project_id' => $project->id, 'title' => 'Child', 'parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(1)
        ->and($child->parent->id)->toBe($parent->id);
});

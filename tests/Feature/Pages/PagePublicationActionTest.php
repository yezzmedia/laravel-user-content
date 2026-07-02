<?php

declare(strict_types=1);

use YezzMedia\Content\Actions\PublishPageAction;
use YezzMedia\Content\Actions\UnpublishPageAction;
use YezzMedia\Content\Models\Page;

it('changes page status to published', function () {
    $project = $this->createProject();
    $page = Page::create([
        'project_id' => $project->id,
        'title' => 'Test',
        'slug' => 'test',
    ]);

    expect($page->isDraft())->toBeTrue();

    app(PublishPageAction::class)->execute($page);
    $page->refresh();

    expect($page->isPublished())->toBeTrue()
        ->and($page->published_at)->not->toBeNull();
});

it('changes page status back to draft', function () {
    $project = $this->createProject();
    $page = Page::create([
        'project_id' => $project->id,
        'title' => 'Test',
        'slug' => 'test',
    ]);
    $page->publish();

    app(UnpublishPageAction::class)->execute($page);
    $page->refresh();

    expect($page->isDraft())->toBeTrue()
        ->and($page->published_at)->toBeNull();
});

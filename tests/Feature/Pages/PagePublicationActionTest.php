<?php

declare(strict_types=1);

use YezzMedia\Content\Actions\PublishPageAction;
use YezzMedia\Content\Actions\UnpublishPageAction;
use YezzMedia\Content\Events\PagePublished;
use YezzMedia\Content\Events\PageUnpublished;
use YezzMedia\Content\Models\Page;
use YezzMedia\UserProjects\Models\Project;

it('dispatches PagePublished event when publishing', function () {
    $project = Project::factory()->create();
    $page = Page::create(['project_id' => $project->id, 'title' => 'Test']);

    $this->expectsEvents(PagePublished::class);

    app(PublishPageAction::class)->execute($page);
});

it('dispatches PageUnpublished event when unpublishing', function () {
    $project = Project::factory()->create();
    $page = Page::create(['project_id' => $project->id, 'title' => 'Test']);
    $page->publish();

    $this->expectsEvents(PageUnpublished::class);

    app(UnpublishPageAction::class)->execute($page);
});

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Actions;

use YezzMedia\Content\Events\PagePublished;
use YezzMedia\Content\Models\Page;

final class PublishPageAction
{
    public function execute(Page $page): void
    {
        $page->publish();

        PagePublished::dispatch(
            projectId: $page->project_id,
            pageId: $page->id,
            publishedAt: $page->published_at->toIso8601String(),
        );
    }
}

<?php

declare(strict_types=1);

namespace YezzMedia\Content\Listeners;

use YezzMedia\Content\Events\PagePublished;
use YezzMedia\Content\Events\PageSlugChanged;
use YezzMedia\Content\Events\PageUnpublished;

final class ContentAuditListener
{
    public function handlePagePublished(PagePublished $event): void
    {
        activity()
            ->event('published')
            ->performedOn(
                \YezzMedia\Content\Models\Page::find($event->pageId),
            )
            ->withProperties([
                'project_id' => $event->projectId,
                'page_id' => $event->pageId,
                'published_at' => $event->publishedAt,
            ])
            ->log('Page published');
    }

    public function handlePageUnpublished(PageUnpublished $event): void
    {
        activity()
            ->event('unpublished')
            ->withProperties([
                'project_id' => $event->projectId,
                'page_id' => $event->pageId,
            ])
            ->log('Page unpublished');
    }

    public function handlePageSlugChanged(PageSlugChanged $event): void
    {
        activity()
            ->event('slug_changed')
            ->withProperties([
                'project_id' => $event->projectId,
                'page_id' => $event->pageId,
                'old_slug' => $event->oldSlug,
                'new_slug' => $event->newSlug,
            ])
            ->log('Page slug changed');
    }
}

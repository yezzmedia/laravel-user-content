<?php

declare(strict_types=1);

namespace YezzMedia\Content\Listeners;

use Throwable;
use YezzMedia\Content\Events\PagePublished;
use YezzMedia\Content\Events\PageSlugChanged;
use YezzMedia\Content\Events\PageUnpublished;
use YezzMedia\Content\Models\Page;

final class ContentAuditListener
{
    public function handlePagePublished(PagePublished $event): void
    {
        try {
            activity()
                ->event('published')
                ->performedOn(
                    Page::find($event->pageId),
                )
                ->withProperties([
                    'project_id' => $event->projectId,
                    'page_id' => $event->pageId,
                    'published_at' => $event->publishedAt,
                ])
                ->log('Page published');
        } catch (Throwable) {
        }
    }

    public function handlePageUnpublished(PageUnpublished $event): void
    {
        try {
            activity()
                ->event('unpublished')
                ->withProperties([
                    'project_id' => $event->projectId,
                    'page_id' => $event->pageId,
                ])
                ->log('Page unpublished');
        } catch (Throwable) {
        }
    }

    public function handlePageSlugChanged(PageSlugChanged $event): void
    {
        try {
            activity()
                ->event('slug_changed')
                ->withProperties([
                    'project_id' => $event->projectId,
                    'page_id' => $event->pageId,
                    'old_slug' => $event->oldSlug,
                    'new_slug' => $event->newSlug,
                ])
                ->log('Page slug changed');
        } catch (Throwable) {
        }
    }
}

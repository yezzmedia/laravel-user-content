<?php

declare(strict_types=1);

namespace YezzMedia\Content\Actions;

use YezzMedia\Content\Events\PageUnpublished;
use YezzMedia\Content\Models\Page;

final class UnpublishPageAction
{
    public function execute(Page $page): void
    {
        $page->unpublish();

        PageUnpublished::dispatch(
            projectId: $page->project_id,
            pageId: $page->id,
        );
    }
}

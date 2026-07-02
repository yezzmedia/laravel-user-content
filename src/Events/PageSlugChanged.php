<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

final class PageSlugChanged
{
    public function __construct(
        public readonly int $projectId,
        public readonly int $pageId,
        public readonly string $oldSlug,
        public readonly string $newSlug,
    ) {}
}

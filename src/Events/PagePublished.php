<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class PagePublished
{
    use Dispatchable;

    public function __construct(
        public readonly int $projectId,
        public readonly int $pageId,
        public readonly string $publishedAt,
    ) {}
}

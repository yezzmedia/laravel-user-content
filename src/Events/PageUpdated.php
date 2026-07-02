<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

final class PageUpdated
{
    public function __construct(
        public readonly int $projectId,
        public readonly int $pageId,
        public readonly array $changedKeys,
    ) {}
}

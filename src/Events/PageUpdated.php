<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class PageUpdated
{
    use Dispatchable;

    public function __construct(
        public readonly int $projectId,
        public readonly int $pageId,
        public readonly array $changedKeys,
    ) {}
}

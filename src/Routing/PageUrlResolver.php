<?php

declare(strict_types=1);

namespace YezzMedia\Content\Routing;

use YezzMedia\Content\Models\Page;

final class PageUrlResolver
{
    public function resolve(string $slug): ?Page
    {
        return Page::query()
            ->published()
            ->bySlug($slug)
            ->first();
    }
}

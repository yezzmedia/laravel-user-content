<?php

declare(strict_types=1);

namespace YezzMedia\Content\Listeners;

use YezzMedia\Content\Events\PageSlugChanged;
use YezzMedia\Content\Models\Redirect;

final class CreateRedirectOnSlugChange
{
    public function handle(PageSlugChanged $event): void
    {
        $oldPath = '/'.$event->oldSlug;
        $newPath = '/'.$event->newSlug;

        $existing = Redirect::query()
            ->forProject($event->projectId)
            ->where('source', $oldPath)
            ->first();

        if ($existing !== null) {
            $existing->update([
                'target' => $newPath,
                'enabled' => true,
            ]);

            return;
        }

        Redirect::create([
            'project_id' => $event->projectId,
            'source' => $oldPath,
            'target' => $newPath,
            'status_code' => 301,
            'enabled' => true,
        ]);
    }
}

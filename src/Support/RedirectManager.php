<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use YezzMedia\Content\Models\Redirect;

class RedirectManager
{
    public function resolve(int $projectId, string $path): ?Redirect
    {
        return Redirect::findBySource($path, $projectId);
    }

    public function resolveFromRequest(string $path, ?int $projectId = null): ?Redirect
    {
        if ($projectId === null) {
            return null;
        }

        $normalized = '/'.trim($path, '/');

        return $this->resolve($projectId, $normalized);
    }
}

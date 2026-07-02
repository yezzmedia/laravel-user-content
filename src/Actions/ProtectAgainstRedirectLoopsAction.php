<?php

declare(strict_types=1);

namespace YezzMedia\Content\Actions;

use YezzMedia\Content\Models\Redirect;

final class ProtectAgainstRedirectLoopsAction
{
    private const MAX_CHAIN_HOPS = 5;

    public function execute(string $source, string $target, int $projectId): bool
    {
        if ($source === $target) {
            return false;
        }

        $visited = [$source, $target];

        $current = $target;

        for ($i = 0; $i < self::MAX_CHAIN_HOPS; $i++) {
            $next = Redirect::findBySource($current, $projectId);

            if ($next === null) {
                return true;
            }

            if (in_array($next->target, $visited, true)) {
                return false;
            }

            $visited[] = $next->target;
            $current = $next->target;
        }

        return false;
    }
}

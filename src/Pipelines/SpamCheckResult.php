<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pipelines;

final class SpamCheckResult
{
    private function __construct(
        public readonly bool $isSpam,
        public readonly ?string $reason,
    ) {}

    public static function passed(): self
    {
        return new self(isSpam: false, reason: null);
    }

    public static function failed(string $reason): self
    {
        return new self(isSpam: true, reason: $reason);
    }
}

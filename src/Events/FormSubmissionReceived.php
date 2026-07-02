<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class FormSubmissionReceived
{
    use Dispatchable;

    public function __construct(
        public readonly int $formDefinitionId,
        public readonly int $submissionId,
        public readonly bool $isSpam,
    ) {}
}

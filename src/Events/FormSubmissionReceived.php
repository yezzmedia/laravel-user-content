<?php

declare(strict_types=1);

namespace YezzMedia\Content\Events;

final class FormSubmissionReceived
{
    public function __construct(
        public readonly int $formDefinitionId,
        public readonly int $submissionId,
        public readonly bool $isSpam,
    ) {}
}

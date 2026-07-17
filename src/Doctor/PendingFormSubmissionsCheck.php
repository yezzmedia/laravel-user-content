<?php

declare(strict_types=1);

namespace YezzMedia\Content\Doctor;

use Throwable;
use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Data\DoctorResult;
use YezzMedia\Foundation\Doctor\DoctorCheck;

final readonly class PendingFormSubmissionsCheck implements DoctorCheck
{
    private const KEY = 'content_pending_form_submissions';

    private const PACKAGE = 'yezzmedia/laravel-user-content';

    public function __construct(private ContentStoreSetup $setup) {}

    public function key(): string
    {
        return self::KEY;
    }

    public function package(): string
    {
        return self::PACKAGE;
    }

    public function run(): DoctorResult
    {
        try {
            $count = $this->setup->pendingSubmissionCount();
        } catch (Throwable $exception) {
            return $this->result(
                status: 'failed',
                message: 'Pending submissions check could not be completed.',
                isBlocking: false,
                context: ['exception' => $exception::class, 'message' => $exception->getMessage()],
            );
        }

        if ($count === 0) {
            return $this->result(
                status: 'passed',
                message: 'No pending form submissions.',
                isBlocking: false,
            );
        }

        return $this->result(
            status: 'info',
            message: $count.' unread form submission(s) in the last 7 days.',
            isBlocking: false,
            context: ['pending_count' => $count],
        );
    }

    private function result(string $status, string $message, bool $isBlocking, ?array $context = null): DoctorResult
    {
        return new DoctorResult(
            key: $this->key(),
            package: $this->package(),
            status: $status,
            message: $message,
            isBlocking: $isBlocking,
            context: $context,
        );
    }
}

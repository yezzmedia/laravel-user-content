<?php

declare(strict_types=1);

namespace YezzMedia\Content\Doctor;

use Throwable;
use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Doctor\DoctorCheck;
use YezzMedia\Foundation\Data\DoctorResult;

final readonly class OrphanedRedirectsCheck implements DoctorCheck
{
    private const KEY = 'content_orphaned_redirects';
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
            $orphaned = $this->setup->orphanedRedirects();
        } catch (Throwable $exception) {
            return $this->result(
                status: 'failed',
                message: 'Orphaned redirect check could not be completed.',
                isBlocking: false,
                context: ['exception' => $exception::class, 'message' => $exception->getMessage()],
            );
        }

        if (count($orphaned) === 0) {
            return $this->result(
                status: 'passed',
                message: 'No orphaned redirects found.',
                isBlocking: false,
            );
        }

        return $this->result(
            status: 'warning',
            message: count($orphaned).' redirect(s) point to non-existent pages.',
            isBlocking: false,
            context: ['orphaned_count' => count($orphaned)],
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

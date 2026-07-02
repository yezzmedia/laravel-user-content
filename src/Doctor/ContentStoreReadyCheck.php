<?php

declare(strict_types=1);

namespace YezzMedia\Content\Doctor;

use Throwable;
use YezzMedia\Content\Support\ContentStoreSetup;
use YezzMedia\Foundation\Doctor\DoctorCheck;
use YezzMedia\Foundation\Data\DoctorResult;

final readonly class ContentStoreReadyCheck implements DoctorCheck
{
    private const KEY = 'content_store_ready';
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
            $ready = $this->setup->allTablesExist();
        } catch (Throwable $exception) {
            return $this->result(
                status: 'failed',
                message: 'The content store could not be inspected.',
                isBlocking: true,
                context: ['exception' => $exception::class, 'message' => $exception->getMessage()],
            );
        }

        if ($ready) {
            return $this->result(
                status: 'passed',
                message: 'All content tables exist.',
                isBlocking: false,
            );
        }

        return $this->result(
            status: 'failed',
            message: 'Content tables are missing. Run `php artisan website:install --migrate`.',
            isBlocking: true,
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

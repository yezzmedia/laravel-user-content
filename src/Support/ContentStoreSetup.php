<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use Illuminate\Support\Facades\Schema;
use YezzMedia\Content\Models\FormSubmission;
use YezzMedia\Content\Models\Page;
use YezzMedia\Content\Models\Redirect;

class ContentStoreSetup
{
    private const CONFIG_KEY = 'user-content';

    public function configPublished(): bool
    {
        return ! is_null(config(static::CONFIG_KEY));
    }

    public function pagesTableExists(): bool
    {
        return Schema::hasTable('pages');
    }

    public function navigationLinksTableExists(): bool
    {
        return Schema::hasTable('navigation_links');
    }

    public function redirectsTableExists(): bool
    {
        return Schema::hasTable('redirects');
    }

    public function formDefinitionsTableExists(): bool
    {
        return Schema::hasTable('form_definitions');
    }

    public function allTablesExist(): bool
    {
        return $this->pagesTableExists()
            && $this->navigationLinksTableExists()
            && $this->redirectsTableExists()
            && $this->formDefinitionsTableExists();
    }

    public function publishConfig(bool $force = false): void
    {
        if (! $force && $this->configPublished()) {
            return;
        }

        $this->ensureConfigDirectoriesExist();

        $source = __DIR__.'/../../config/user-content.php';
        $target = config_path('user-content.php');

        if (! file_exists($target) || $force) {
            copy($source, $target);
        }
    }

    private function ensureConfigDirectoriesExist(): void
    {
        $dir = dirname(config_path('user-content.php'));
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    public function orphanedRedirects(): array
    {
        if (! $this->redirectsTableExists()) {
            return [];
        }

        $redirects = Redirect::enabled()->get();

        return $redirects->filter(function ($redirect) {
            $slug = ltrim($redirect->target, '/');
            $page = Page::bySlug($slug)->first();

            return $page === null || ! $page->isPublished();
        })->values()->all();
    }

    public function pendingSubmissionCount(): int
    {
        if (! $this->formDefinitionsTableExists()) {
            return 0;
        }

        return FormSubmission::where('is_spam', false)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    }
}

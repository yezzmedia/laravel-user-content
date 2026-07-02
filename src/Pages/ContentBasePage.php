<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use YezzMedia\Dashboard\Pages\DashboardPage;
use YezzMedia\UserProjects\Models\Project;

abstract class ContentBasePage extends DashboardPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected ?Project $project = null;

    protected ?int $projectId = null;

    public static function canAccess(): bool
    {
        return auth(config('user-projects.panel.guard', 'web'))->check();
    }

    abstract protected function getPageTitle(): string;

    abstract protected function getPageDescription(): string;

    abstract protected function pageData(): array;

    protected function resolveProject(): void
    {
        $projectId = request()->query('project');

        if ($projectId === null) {
            return;
        }

        $user = auth(config('user-projects.panel.guard', 'web'))->user();

        if ($user === null) {
            return;
        }

        $this->projectId = (int) $projectId;
        $this->project = Project::query()
            ->where('id', $this->projectId)
            ->whereHas('members', fn ($q) => $q->where('user_id', $user->id))
            ->first();
    }

    protected function getViewData(): array
    {
        $this->resolveProject();

        return [
            'pageTitle' => $this->getPageTitle(),
            'pageData' => $this->pageData(),
            'pageDescription' => $this->getPageDescription(),
            'project' => $this->project,
            'projectId' => $this->projectId,
        ];
    }

    public function getTitle(): string
    {
        $title = $this->getPageTitle();

        if ($this->project !== null) {
            $title .= ' — '.$this->project->name;
        }

        return $title;
    }
}

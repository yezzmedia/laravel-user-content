<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pages;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use YezzMedia\Content\Actions\PublishPageAction;
use YezzMedia\Content\Actions\UnpublishPageAction;
use YezzMedia\Content\Enums\PageStatus;
use YezzMedia\Content\Models\Page;

class PagesOverviewPage extends ContentBasePage
{
    protected string $view = 'user-content::pages.pages-overview';

    protected static ?string $slug = 'content/pages';

    #[Url(as: 'edit')]
    public ?int $editPageId = null;

    public bool $showCreateModal = false;

    public string $createTitle = '';

    public string $createContent = '';

    public bool $createShowInNavigation = true;

    public ?int $createParentId = null;

    public string $createSortOrder = '0';

    public string $createError = '';

    public bool $showEditModal = false;

    #[Locked]
    public ?int $editingPageId = null;

    public string $editTitle = '';

    public string $editContent = '';

    public bool $editShowInNavigation = true;

    public ?int $editParentId = null;

    public string $editSortOrder = '';

    public string $editError = '';

    protected function getPageTitle(): string
    {
        return 'Pages';
    }

    protected function getPageDescription(): string
    {
        return 'Manage website pages, content, and publication status.';
    }

    protected function pageData(): array
    {
        if ($this->projectId === null) {
            return ['pages' => []];
        }

        return [
            'pages' => Page::query()
                ->forProject($this->projectId)
                ->with('parent')
                ->orderBy('sort_order')
                ->get(),
        ];
    }

    public function mount(): void
    {
        if ($this->editPageId !== null && $this->projectId !== null) {
            $id = $this->editPageId;
            $this->editPageId = null;
            $this->editPage($id);
        }
    }

    public function openCreateModal(): void
    {
        $this->createTitle = '';
        $this->createContent = '';
        $this->createShowInNavigation = true;
        $this->createParentId = null;
        $this->createSortOrder = '0';
        $this->createError = '';
        $this->showCreateModal = true;
    }

    public function createPage(): void
    {
        $this->validate([
            'createTitle' => ['required', 'string', 'max:255'],
            'createSortOrder' => ['nullable', 'integer'],
        ], [
            'createTitle.required' => 'The page title is required.',
            'createSortOrder.integer' => 'The sort order must be a number.',
        ]);

        Page::query()->create([
            'project_id' => $this->projectId,
            'title' => $this->createTitle,
            'content' => $this->createContent ?: null,
            'status' => PageStatus::Draft,
            'show_in_navigation' => $this->createShowInNavigation,
            'parent_id' => $this->createParentId ?: null,
            'sort_order' => $this->createSortOrder !== '' ? (int) $this->createSortOrder : 0,
        ]);

        $this->showCreateModal = false;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->createError = '';
    }

    public function editPage(int $pageId): void
    {
        $page = Page::query()->forProject($this->projectId)->findOrFail($pageId);

        $this->editingPageId = $page->id;
        $this->editTitle = $page->title;
        $this->editContent = $page->content ?? '';
        $this->editShowInNavigation = (bool) $page->show_in_navigation;
        $this->editParentId = $page->parent_id;
        $this->editSortOrder = (string) $page->sort_order;
        $this->editError = '';
        $this->showEditModal = true;
    }

    public function updatePage(): void
    {
        if ($this->editingPageId === null) {
            $this->showEditModal = false;

            return;
        }

        $this->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editSortOrder' => ['nullable', 'integer'],
        ], [
            'editTitle.required' => 'The page title is required.',
            'editSortOrder.integer' => 'The sort order must be a number.',
        ]);

        $page = Page::query()->forProject($this->projectId)->findOrFail($this->editingPageId);

        $page->update([
            'title' => $this->editTitle,
            'content' => $this->editContent ?: null,
            'show_in_navigation' => $this->editShowInNavigation,
            'parent_id' => $this->editParentId ?: null,
            'sort_order' => $this->editSortOrder !== '' ? (int) $this->editSortOrder : 0,
        ]);

        $this->showEditModal = false;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editError = '';
    }

    public function togglePublish(int $pageId): void
    {
        $page = Page::query()->forProject($this->projectId)->findOrFail($pageId);

        if ($page->isPublished()) {
            app(UnpublishPageAction::class)->execute($page);
        } else {
            app(PublishPageAction::class)->execute($page);
        }
    }

    public function deletePage(int $pageId): void
    {
        $page = Page::query()->forProject($this->projectId)->findOrFail($pageId);
        $page->delete();
    }
}

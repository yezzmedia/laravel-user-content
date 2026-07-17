<div class="space-y-6">
    <x-user-projects::page-header
        :title="$pageTitle"
        :subtitle="$pageDescription"
        color="indigo"
    >
        <x-slot:icon>
            <x-user-projects::icon name="folder" class="h-5 w-5" />
        </x-slot:icon>
        @if ($projectId)
            <x-slot:actions>
                <button
                    wire:click="openCreateModal"
                    class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950"
                >
                    <x-user-projects::icon name="plus" class="h-3.5 w-3.5" />
                    Create Page
                </button>
            </x-slot:actions>
        @endif
    </x-user-projects::page-header>

    @if ($projectId)
        @if (count($pageData['pages'] ?? []) > 0)
            <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="px-4 pt-4 sm:px-6 sm:pt-6">
                    <x-user-projects::section-header :title="__('All Pages')" color="indigo" />
                </div>
                <div class="p-4 pt-0 sm:p-6 sm:pt-0">
                    <div class="-mx-4 -mb-4 overflow-hidden sm:-mx-6 sm:-mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Title</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Status</th>
                                <th scope="col" class="hidden px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell sm:px-6">Slug</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($pageData['pages'] as $page)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900 sm:px-6 dark:text-gray-100">
                                        {{ $page->title }}
                                    </td>
                                    <td class="px-4 py-4 text-sm sm:px-6">
                                        @if ($page->isPublished())
                                            <x-user-projects::badge class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Published</x-user-projects::badge>
                                        @else
                                            <x-user-projects::badge class="bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Draft</x-user-projects::badge>
                                        @endif
                                    </td>
                                    <td class="hidden px-4 py-4 text-sm text-gray-500 sm:table-cell sm:px-6 dark:text-gray-400">
                                        /{{ $page->slug }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm sm:px-6">
                                        <div class="flex items-center justify-end gap-1">
                                            <a
                                                href="{{ url('/'.$page->slug) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1 border border-gray-300 bg-white px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                title="Preview"
                                            >
                                                <x-user-projects::icon name="magnifying-glass" class="h-3.5 w-3.5" />
                                            </a>
                                            <button
                                                wire:click="editPage({{ $page->id }})"
                                                class="inline-flex items-center gap-1 border border-gray-300 bg-white px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                title="Edit"
                                            >
                                                <x-user-projects::icon name="pencil-square" class="h-3.5 w-3.5" />
                                            </button>
                                            <button
                                                wire:click="togglePublish({{ $page->id }})"
                                                class="inline-flex items-center gap-1 border border-gray-300 bg-white px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                title="{{ $page->isPublished() ? 'Unpublish' : 'Publish' }}"
                                            >
                                                @if ($page->isPublished())
                                                    <x-user-projects::icon name="arrow-down" class="h-3.5 w-3.5" />
                                                @else
                                                    <x-user-projects::icon name="arrow-up" class="h-3.5 w-3.5" />
                                                @endif
                                            </button>
                                            <button
                                                wire:click="deletePage({{ $page->id }})"
                                                wire:confirm="Are you sure you want to delete this page?"
                                                class="inline-flex items-center gap-1 border border-rose-300 bg-white px-2 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50 dark:border-rose-700 dark:text-rose-300 dark:hover:bg-rose-950"
                                                title="Delete"
                                            >
                                                <x-user-projects::icon name="trash" class="h-3.5 w-3.5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        @else
            <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="p-4 sm:p-6">
                    <x-user-projects::empty-state
                        :title="__('No pages yet')"
                        :description="__('Create your first page to get started.')"
                        icon="folder"
                    >
                        <x-slot:action>
                            <button
                                wire:click="openCreateModal"
                                class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950"
                            >
                                <x-user-projects::icon name="plus" class="h-3.5 w-3.5" />
                                Create Page
                            </button>
                        </x-slot:action>
                    </x-user-projects::empty-state>
                </div>
            </div>
        @endif
    @else
        <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="p-4 sm:p-6">
                <x-user-projects::empty-state
                    :title="__('No project selected')"
                    :description="__('Select a project from the sidebar to manage its pages.')"
                    icon="folder"
                />
            </div>
        </div>
    @endif

    {{-- Create Page Modal --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" wire:click="closeCreateModal"></div>

                <div class="relative transform overflow-hidden border border-gray-200 bg-white text-left shadow-xl transition-all dark:border-gray-700 dark:bg-gray-900 sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="px-4 pb-4 pt-5 sm:p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Create Page</h3>
                            <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="text-lg leading-none">&times;</span>
                            </button>
                        </div>

                        @if ($createError)
                            <div class="mt-4 border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700 dark:border-rose-700 dark:bg-rose-900/20 dark:text-rose-400">
                                {{ $createError }}
                            </div>
                        @endif

                        <form wire:submit.prevent="createPage" class="mt-4 space-y-4">
                            <div>
                                <label for="createTitle" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Title</label>
                                <input
                                    type="text"
                                    id="createTitle"
                                    wire:model="createTitle"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    autofocus
                                />
                            </div>

                            <div>
                                <label for="createContent" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Content</label>
                                <textarea
                                    id="createContent"
                                    wire:model="createContent"
                                    rows="8"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                ></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    id="createShowInNavigation"
                                    wire:model="createShowInNavigation"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="createShowInNavigation" class="text-sm text-gray-700 dark:text-gray-300">Show in navigation</label>
                            </div>

                            <div>
                                <label for="createParentId" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Parent Page</label>
                                <select
                                    id="createParentId"
                                    wire:model="createParentId"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">None</option>
                                    @foreach (\YezzMedia\Content\Models\Page::query()->forProject($projectId)->orderBy('title')->get() as $parentOption)
                                        <option value="{{ $parentOption->id }}">{{ $parentOption->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="createSortOrder" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Sort Order</label>
                                <input
                                    type="number"
                                    id="createSortOrder"
                                    wire:model="createSortOrder"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    wire:click="closeCreateModal"
                                    class="inline-flex items-center gap-1 border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-1 border border-indigo-300 bg-white px-3 py-2 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950"
                                >
                                    Create Page
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Page Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" wire:click="closeEditModal"></div>

                <div class="relative transform overflow-hidden border border-gray-200 bg-white text-left shadow-xl transition-all dark:border-gray-700 dark:bg-gray-900 sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="px-4 pb-4 pt-5 sm:p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Page</h3>
                            <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="text-lg leading-none">&times;</span>
                            </button>
                        </div>

                        @if ($editError)
                            <div class="mt-4 border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700 dark:border-rose-700 dark:bg-rose-900/20 dark:text-rose-400">
                                {{ $editError }}
                            </div>
                        @endif

                        <form wire:submit.prevent="updatePage" class="mt-4 space-y-4">
                            <div>
                                <label for="editTitle" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Title</label>
                                <input
                                    type="text"
                                    id="editTitle"
                                    wire:model="editTitle"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div>
                                <label for="editContent" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Content</label>
                                <textarea
                                    id="editContent"
                                    wire:model="editContent"
                                    rows="8"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                ></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    id="editShowInNavigation"
                                    wire:model="editShowInNavigation"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="editShowInNavigation" class="text-sm text-gray-700 dark:text-gray-300">Show in navigation</label>
                            </div>

                            <div>
                                <label for="editParentId" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Parent Page</label>
                                <select
                                    id="editParentId"
                                    wire:model="editParentId"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">None</option>
                                    @foreach (\YezzMedia\Content\Models\Page::query()->forProject($projectId)->where('id', '!=', $editingPageId)->orderBy('title')->get() as $parentOption)
                                        <option value="{{ $parentOption->id }}">{{ $parentOption->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="editSortOrder" class="block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Sort Order</label>
                                <input
                                    type="number"
                                    id="editSortOrder"
                                    wire:model="editSortOrder"
                                    class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    wire:click="closeEditModal"
                                    class="inline-flex items-center gap-1 border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-1 border border-indigo-300 bg-white px-3 py-2 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

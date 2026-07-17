<div class="space-y-6">
    <x-user-projects::page-header
        :title="$pageTitle"
        :subtitle="$pageDescription"
        color="indigo"
    >
        <x-slot:icon>
            <x-user-projects::icon name="bars-3" class="h-5 w-5" />
        </x-slot:icon>
        @if ($projectId)
            <x-slot:actions>
                <button class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950">
                    <x-user-projects::icon name="plus" class="h-3.5 w-3.5" />
                    Add Link
                </button>
            </x-slot:actions>
        @endif
    </x-user-projects::page-header>

    @if ($projectId)
        @if (count($pageData['links'] ?? []) > 0)
            <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="px-4 pt-4 sm:px-6 sm:pt-6">
                    <x-user-projects::section-header :title="__('Menu Links')" color="indigo" />
                </div>
                <div class="p-4 pt-0 sm:p-6 sm:pt-0">
                    <div class="-mx-4 -mb-4 overflow-hidden sm:-mx-6 sm:-mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Label</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">URL</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Section</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Sort</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($pageData['links'] as $link)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900 sm:px-6 dark:text-gray-100">{{ $link->resolvedLabel() }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-500 sm:px-6 dark:text-gray-400">{{ $link->resolvedUrl() }}</td>
                                    <td class="px-4 py-4 text-sm sm:px-6">
                                        <x-user-projects::badge class="bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $link->section }}</x-user-projects::badge>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 sm:px-6 dark:text-gray-400">{{ $link->sort_order }}</td>
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
                        :title="__('No navigation links yet')"
                        :description="__('Add your first link to build a menu.')"
                        icon="bars-3"
                    />
                </div>
            </div>
        @endif
    @else
        <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="p-4 sm:p-6">
                <x-user-projects::empty-state
                    :title="__('No project selected')"
                    :description="__('Select a project from the sidebar to manage its navigation links.')"
                    icon="bars-3"
                />
            </div>
        </div>
    @endif
</div>

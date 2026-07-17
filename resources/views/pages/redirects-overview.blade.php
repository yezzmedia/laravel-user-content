<div class="space-y-6">
    <x-user-projects::page-header
        :title="$pageTitle"
        :subtitle="$pageDescription"
        color="indigo"
    >
        <x-slot:icon>
            <x-user-projects::icon name="arrow-right-on-rectangle" class="h-5 w-5" />
        </x-slot:icon>
        @if ($projectId)
            <x-slot:actions>
                <button class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950">
                    <x-user-projects::icon name="plus" class="h-3.5 w-3.5" />
                    Create Redirect
                </button>
            </x-slot:actions>
        @endif
    </x-user-projects::page-header>

    @if ($projectId)
        @if (count($pageData['redirects'] ?? []) > 0)
            <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="px-4 pt-4 sm:px-6 sm:pt-6">
                    <x-user-projects::section-header :title="__('Redirect Rules')" color="indigo" />
                </div>
                <div class="p-4 pt-0 sm:p-6 sm:pt-0">
                    <div class="-mx-4 -mb-4 overflow-hidden sm:-mx-6 sm:-mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Source</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Target</th>
                                <th scope="col" class="hidden px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell sm:px-6">Status</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Enabled</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($pageData['redirects'] as $redirect)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4 text-sm font-mono text-gray-500 sm:px-6 dark:text-gray-400">{{ $redirect->source }}</td>
                                    <td class="px-4 py-4 text-sm font-mono text-gray-500 sm:px-6 dark:text-gray-400">{{ $redirect->target }}</td>
                                    <td class="hidden px-4 py-4 text-sm text-gray-500 sm:table-cell sm:px-6 dark:text-gray-400">{{ $redirect->status_code }}</td>
                                    <td class="px-4 py-4 text-sm sm:px-6">
                                        @if ($redirect->enabled)
                                            <x-user-projects::badge class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Enabled</x-user-projects::badge>
                                        @else
                                            <x-user-projects::badge class="bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Disabled</x-user-projects::badge>
                                        @endif
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
                        :title="__('No redirects yet')"
                        :description="__('Redirects are automatically created when page slugs change.')"
                        icon="arrow-right-on-rectangle"
                    />
                </div>
            </div>
        @endif
    @else
        <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="p-4 sm:p-6">
                <x-user-projects::empty-state
                    :title="__('No project selected')"
                    :description="__('Select a project from the sidebar to manage its redirects.')"
                    icon="arrow-right-on-rectangle"
                />
            </div>
        </div>
    @endif
</div>

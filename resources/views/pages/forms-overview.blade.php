<div class="space-y-6">
    <x-user-projects::page-header
        :title="$pageTitle"
        :subtitle="$pageDescription"
        color="indigo"
    >
        <x-slot:icon>
            <x-user-projects::icon name="clipboard-document-list" class="h-5 w-5" />
        </x-slot:icon>
        @if ($projectId)
            <x-slot:actions>
                <button class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-950">
                    <x-user-projects::icon name="plus" class="h-3.5 w-3.5" />
                    Create Form
                </button>
            </x-slot:actions>
        @endif
    </x-user-projects::page-header>

    @if ($projectId)
        @if (count($pageData['forms'] ?? []) > 0)
            <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="px-4 pt-4 sm:px-6 sm:pt-6">
                    <x-user-projects::section-header :title="__('Form Definitions')" color="indigo" />
                </div>
                <div class="p-4 pt-0 sm:p-6 sm:pt-0">
                    <div class="-mx-4 -mb-4 overflow-hidden sm:-mx-6 sm:-mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Fields</th>
                                <th scope="col" class="hidden px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell sm:px-6">Submissions</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:px-6">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($pageData['forms'] as $form)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900 sm:px-6 dark:text-gray-100">{{ $form->name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-500 sm:px-6 dark:text-gray-400">{{ count($form->getFieldList()) }}</td>
                                    <td class="hidden px-4 py-4 text-sm text-gray-500 sm:table-cell sm:px-6 dark:text-gray-400">{{ $form->submissions_count ?? 0 }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-500 sm:px-6 dark:text-gray-400">{{ $form->created_at?->format('M j, Y') }}</td>
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
                        :title="__('No forms yet')"
                        :description="__('Create your first form definition to start collecting submissions.')"
                        icon="clipboard-document-list"
                    />
                </div>
            </div>
        @endif
    @else
        <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="p-4 sm:p-6">
                <x-user-projects::empty-state
                    :title="__('No project selected')"
                    :description="__('Select a project from the sidebar to manage its forms.')"
                    icon="clipboard-document-list"
                />
            </div>
        </div>
    @endif
</div>

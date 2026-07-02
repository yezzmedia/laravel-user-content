<x-dashboard::layout>
    <x-slot:title>Pages</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Pages</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage website pages, content, and publication status.</p>
            </div>
            <x-dashboard::button href="#" class="bg-indigo-600 text-white hover:bg-indigo-700">
                Create Page
            </x-dashboard::button>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            @if (count($pageData['pages'] ?? []) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Slug</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pageData['pages'] as $page)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium">{{ $page->title }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold {{ $page->isPublished() ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $page->status->value }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $page->slug }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $page->updated_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No pages yet. Create your first page to get started.
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layout>

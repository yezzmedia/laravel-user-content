<x-dashboard::layout>
    <x-slot:title>Navigation</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Navigation</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage header and footer menu links.</p>
            </div>
            <x-dashboard::button href="#" class="bg-indigo-600 text-white hover:bg-indigo-700">
                Add Link
            </x-dashboard::button>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            @if (count($pageData['links'] ?? []) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Label</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">URL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Section</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Sort</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pageData['links'] as $link)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium">{{ $link->resolvedLabel() }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $link->resolvedUrl() }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $link->section }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $link->sort_order }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No navigation links yet. Add your first link to build a menu.
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layout>

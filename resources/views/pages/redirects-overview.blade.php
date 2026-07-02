<x-dashboard::layout>
    <x-slot:title>Redirects</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Redirects</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage URL redirect rules.</p>
            </div>
            <x-dashboard::button href="#" class="bg-indigo-600 text-white hover:bg-indigo-700">
                Create Redirect
            </x-dashboard::button>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            @if (count($pageData['redirects'] ?? []) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Source</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Target</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Enabled</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pageData['redirects'] as $redirect)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-mono text-gray-500 dark:text-gray-400">{{ $redirect->source }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-mono text-gray-500 dark:text-gray-400">{{ $redirect->target }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $redirect->status_code }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold {{ $redirect->enabled ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                        {{ $redirect->enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No redirects yet. Redirects are automatically created when page slugs change.
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layout>

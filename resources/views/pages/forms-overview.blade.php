<x-dashboard::layout>
    <x-slot:title>Forms</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Forms</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage form definitions and view submissions.</p>
            </div>
            <x-dashboard::button href="#" class="bg-indigo-600 text-white hover:bg-indigo-700">
                Create Form
            </x-dashboard::button>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            @if (count($pageData['forms'] ?? []) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Fields</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Submissions</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pageData['forms'] as $form)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium">{{ $form->name }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ count($form->getFieldList()) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $form->submissions_count ?? 0 }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $form->created_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No forms yet. Create your first form definition to start collecting submissions.
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layout>

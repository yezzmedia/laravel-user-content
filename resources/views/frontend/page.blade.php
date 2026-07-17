<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->seo_title ?: $page->title }}</title>
    @if ($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
</head>
<body class="bg-white text-gray-900 antialiased">
    {{-- Admin Edit Bar --}}
    @if ($isAdmin)
        <div class="border-b border-indigo-200 bg-indigo-50 px-4 py-2 dark:border-indigo-700 dark:bg-indigo-950/50">
            <div class="mx-auto flex max-w-4xl items-center justify-between">
                <span class="text-xs font-medium text-indigo-700 dark:text-indigo-300">
                    You are viewing this page as an admin.
                </span>
                <a
                    href="{{ url('/hub/content/pages?project='.$page->project_id.'&edit='.$page->id) }}"
                    class="inline-flex items-center gap-1.5 border border-indigo-300 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-700 dark:bg-indigo-300 dark:hover:bg-indigo-950"
                >
                    Edit Page
                </a>
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <article>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                {{ $page->title }}
            </h1>

            @if ($page->published_at)
                <time datetime="{{ $page->published_at->toDateString() }}" class="mt-3 block text-sm text-gray-500">
                    Published {{ $page->published_at->format('F j, Y') }}
                </time>
            @endif

            <div class="prose prose-lg mt-8 max-w-none">
                {!! $page->content !!}
            </div>
        </article>
    </main>
</body>
</html>

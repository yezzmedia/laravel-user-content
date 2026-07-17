<?php

declare(strict_types=1);

namespace YezzMedia\Content\Http\Controllers;

use Illuminate\Contracts\View\View;
use YezzMedia\Content\Routing\PageUrlResolver;

final class PageController
{
    public function __construct(private readonly PageUrlResolver $resolver) {}

    public function show(string $slug): View
    {
        $page = $this->resolver->resolve($slug);

        if ($page === null) {
            abort(404);
        }

        $isAdmin = auth(config('user-projects.panel.guard', 'web'))->check();

        return view('user-content::frontend.page', [
            'page' => $page,
            'isAdmin' => $isAdmin,
        ]);
    }
}

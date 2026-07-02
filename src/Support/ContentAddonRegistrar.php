<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use YezzMedia\UserProjects\Support\ProjectAddon;
use YezzMedia\UserProjects\Support\ProjectAddonManager;

class ContentAddonRegistrar
{
    public function register(ProjectAddonManager $manager): void
    {
        $manager->register(new ProjectAddon(
            key: 'content.pages',
            label: 'Pages',
            icon: 'document-text',
            description: 'Manage website pages, content, and publication status.',
            urlGenerator: fn ($project) => url('/hub/projects?project='.$project->id.'&tab=pages'),
            sort: 20,
        ));

        $manager->register(new ProjectAddon(
            key: 'content.navigation',
            label: 'Navigation',
            icon: 'bars-3',
            description: 'Manage header and footer menu links.',
            urlGenerator: fn ($project) => url('/hub/projects?project='.$project->id.'&tab=navigation'),
            sort: 30,
        ));

        $manager->register(new ProjectAddon(
            key: 'content.redirects',
            label: 'Redirects',
            icon: 'arrow-right-on-rectangle',
            description: 'Manage URL redirect rules.',
            urlGenerator: fn ($project) => url('/hub/projects?project='.$project->id.'&tab=redirects'),
            sort: 40,
        ));

        $manager->register(new ProjectAddon(
            key: 'content.forms',
            label: 'Forms',
            icon: 'clipboard-document-list',
            description: 'Manage form definitions and view submissions.',
            urlGenerator: fn ($project) => url('/hub/projects?project='.$project->id.'&tab=forms'),
            sort: 50,
        ));
    }
}

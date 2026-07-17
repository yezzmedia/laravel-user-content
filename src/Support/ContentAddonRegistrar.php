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
            key: 'content',
            label: 'Content',
            icon: 'puzzle-piece',
            description: 'Manage pages, navigation links, redirects, and forms for this project.',
            urlGenerator: fn ($project) => url('/hub/content/pages?project='.$project->id),
            sort: 20,
            subItems: [
                [
                    'label' => 'Pages',
                    'icon' => 'document-text',
                    'urlGenerator' => fn ($project) => url('/hub/content/pages?project='.$project->id),
                ],
                [
                    'label' => 'Navigation',
                    'icon' => 'bars-3',
                    'urlGenerator' => fn ($project) => url('/hub/content/navigation?project='.$project->id),
                ],
                [
                    'label' => 'Redirects',
                    'icon' => 'arrow-right-on-rectangle',
                    'urlGenerator' => fn ($project) => url('/hub/content/redirects?project='.$project->id),
                ],
                [
                    'label' => 'Forms',
                    'icon' => 'clipboard-document-list',
                    'urlGenerator' => fn ($project) => url('/hub/content/forms?project='.$project->id),
                ],
            ],
        ));
    }
}

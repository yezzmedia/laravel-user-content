# yezzmedia/laravel-user-content

Customer-managed pages, navigation, redirects, and forms for Yezz Media customer projects.

## Modules

- **Pages** — Page model with slugs (`spatie/laravel-sluggable`), publication workflow, automatic navigation via `show_in_navigation` flag.
- **Navigation** — Menu link management with page-bound and external URLs, section support (header/footer), and breadcrumb building.
- **Redirects** — Exact-match redirects with 301/302 support, loop protection, and automatic redirect creation on page slug changes.
- **Forms** — Form definitions with configurable field types, submission storage, honeypot spam protection, IP-based rate limiting, and mail delivery hooks.

## Requirements

- PHP 8.3+
- Laravel 13+
- `yezzmedia/laravel-foundation` ^0.2
- `yezzmedia/laravel-user-projects` ^0.2
- `spatie/laravel-sluggable` ^3.8
- `spatie/laravel-honeypot` ^4.7
- `filament/filament` ^5.0

## Installation

```bash
composer require yezzmedia/laravel-user-content
```

The service provider is auto-discovered.

Run the install command to publish and execute migrations:

```bash
php artisan website:install
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=user-content-config
```

## Usage

### Pages

Pages are scoped to a project. Create pages through the project admin UI (Hub → Select Project → Pages).

```php
use YezzMedia\Content\Models\Page;

$pages = Page::published()->inNavigation()->get();
```

### Navigation

Navigation menus are built from published pages with `show_in_navigation = true` plus external `NavigationLink` records.

```php
use YezzMedia\Content\Support\NavigationManager;

$menu = app(NavigationManager::class)->buildMenu($project, 'header');
```

### Redirects

Redirects resolve by exact source path match:

```php
use YezzMedia\Content\Support\RedirectManager;

$redirect = app(RedirectManager::class)->resolve($project, '/old-page');
```

### Forms

Form definitions with configurable fields. Submissions are stored with spam protection.

```
GET /{project}/form/{formDefinition}  → Livewire SubmitForm component
```

## Events

- `YezzMedia\Content\Events\PagePublished`
- `YezzMedia\Content\Events\PageUnpublished`
- `YezzMedia\Content\Events\PageSlugChanged`
- `YezzMedia\Content\Events\PageCreated`
- `YezzMedia\Content\Events\PageUpdated`
- `YezzMedia\Content\Events\PageDeleted`
- `YezzMedia\Content\Events\FormSubmissionReceived`

## Permissions

- `content.pages.view`, `content.pages.create`, `content.pages.update`, `content.pages.publish`
- `content.navigation.manage`
- `content.redirects.manage`
- `content.forms.manage`, `content.forms.submissions.view`, `content.forms.submissions.export`

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

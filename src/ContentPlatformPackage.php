<?php

declare(strict_types=1);

namespace YezzMedia\Content;

use YezzMedia\Content\Doctor\ContentStoreReadyCheck;
use YezzMedia\Content\Doctor\OrphanedRedirectsCheck;
use YezzMedia\Content\Doctor\PendingFormSubmissionsCheck;
use YezzMedia\Content\Install\EnsureContentStoreReadyInstallStep;
use YezzMedia\Content\Install\PublishContentConfigInstallStep;
use YezzMedia\Content\Install\PublishContentMigrationsInstallStep;
use YezzMedia\Foundation\Contracts\DefinesAuditEvents;
use YezzMedia\Foundation\Contracts\DefinesInstallSteps;
use YezzMedia\Foundation\Contracts\DefinesPermissions;
use YezzMedia\Foundation\Contracts\DefinesRateLimiters;
use YezzMedia\Foundation\Contracts\PlatformPackage;
use YezzMedia\Foundation\Contracts\ProvidesDoctorChecks;
use YezzMedia\Foundation\Contracts\RegistersFeatures;
use YezzMedia\Foundation\Data\AuditEventDefinition;
use YezzMedia\Foundation\Data\FeatureDefinition;
use YezzMedia\Foundation\Data\PackageMetadata;
use YezzMedia\Foundation\Data\PermissionDefinition;
use YezzMedia\Foundation\Data\RateLimitDefinition;
use YezzMedia\Foundation\Install\InstallStep;

final class ContentPlatformPackage implements
    DefinesAuditEvents,
    DefinesInstallSteps,
    DefinesPermissions,
    DefinesRateLimiters,
    PlatformPackage,
    ProvidesDoctorChecks,
    RegistersFeatures
{
    public function metadata(): PackageMetadata
    {
        return new PackageMetadata(
            name: 'yezzmedia/laravel-user-content',
            vendor: 'yezzmedia',
            description: 'Customer-managed pages, navigation, redirects, and forms for customer projects.',
            packageClass: self::class,
        );
    }

    public function permissionDefinitions(): array
    {
        return [
            new PermissionDefinition(
                name: 'content.pages.view',
                package: 'yezzmedia/laravel-user-content',
                label: 'View content pages',
                description: 'Allows viewing pages within a project.',
            ),
            new PermissionDefinition(
                name: 'content.pages.create',
                package: 'yezzmedia/laravel-user-content',
                label: 'Create content pages',
                description: 'Allows creating new pages within a project.',
            ),
            new PermissionDefinition(
                name: 'content.pages.update',
                package: 'yezzmedia/laravel-user-content',
                label: 'Update content pages',
                description: 'Allows editing existing pages within a project.',
            ),
            new PermissionDefinition(
                name: 'content.pages.publish',
                package: 'yezzmedia/laravel-user-content',
                label: 'Publish content pages',
                description: 'Allows publishing and unpublishing pages.',
            ),
            new PermissionDefinition(
                name: 'content.navigation.manage',
                package: 'yezzmedia/laravel-user-content',
                label: 'Manage navigation',
                description: 'Allows managing navigation links within a project.',
            ),
            new PermissionDefinition(
                name: 'content.redirects.manage',
                package: 'yezzmedia/laravel-user-content',
                label: 'Manage redirects',
                description: 'Allows creating and editing redirect rules.',
            ),
            new PermissionDefinition(
                name: 'content.forms.manage',
                package: 'yezzmedia/laravel-user-content',
                label: 'Manage form definitions',
                description: 'Allows creating and editing form definitions.',
            ),
            new PermissionDefinition(
                name: 'content.forms.submissions.view',
                package: 'yezzmedia/laravel-user-content',
                label: 'View form submissions',
                description: 'Allows viewing submitted form entries.',
            ),
            new PermissionDefinition(
                name: 'content.forms.submissions.export',
                package: 'yezzmedia/laravel-user-content',
                label: 'Export form submissions',
                description: 'Allows exporting form submission data.',
            ),
        ];
    }

    public function featureDefinitions(): array
    {
        return [
            new FeatureDefinition(
                name: 'content.pages',
                package: 'yezzmedia/laravel-user-content',
                label: 'Content pages',
                description: 'Page management with slug generation, publication workflow, and navigation support.',
            ),
            new FeatureDefinition(
                name: 'content.navigation',
                package: 'yezzmedia/laravel-user-content',
                label: 'Navigation management',
                description: 'Menu link management with page-bound and external URLs, section support, and breadcrumbs.',
            ),
            new FeatureDefinition(
                name: 'content.redirects',
                package: 'yezzmedia/laravel-user-content',
                label: 'Redirect management',
                description: 'Exact-match redirects with loop protection and automatic redirects on slug changes.',
            ),
            new FeatureDefinition(
                name: 'content.forms',
                package: 'yezzmedia/laravel-user-content',
                label: 'Form management',
                description: 'Form definitions, submission storage, spam protection, and delivery hooks.',
            ),
        ];
    }

    public function auditEventDefinitions(): array
    {
        return [
            new AuditEventDefinition(
                key: 'content.page.created',
                package: 'yezzmedia/laravel-user-content',
                action: 'created',
                subjectType: 'content_page',
                description: 'A new page was created in a project.',
                severity: 'info',
                contextKeys: ['project_id', 'page_id', 'page_title'],
            ),
            new AuditEventDefinition(
                key: 'content.page.updated',
                package: 'yezzmedia/laravel-user-content',
                action: 'updated',
                subjectType: 'content_page',
                description: 'A page was updated.',
                severity: 'info',
                contextKeys: ['project_id', 'page_id', 'changed_keys'],
            ),
            new AuditEventDefinition(
                key: 'content.page.published',
                package: 'yezzmedia/laravel-user-content',
                action: 'published',
                subjectType: 'content_page',
                description: 'A page was published.',
                severity: 'info',
                contextKeys: ['project_id', 'page_id', 'published_at'],
            ),
            new AuditEventDefinition(
                key: 'content.page.unpublished',
                package: 'yezzmedia/laravel-user-content',
                action: 'unpublished',
                subjectType: 'content_page',
                description: 'A page was taken offline.',
                severity: 'warning',
                contextKeys: ['project_id', 'page_id'],
            ),
            new AuditEventDefinition(
                key: 'content.page.slug_changed',
                package: 'yezzmedia/laravel-user-content',
                action: 'slug_changed',
                subjectType: 'content_page',
                description: 'A page slug was changed and a redirect was created.',
                severity: 'info',
                contextKeys: ['project_id', 'page_id', 'old_slug', 'new_slug'],
            ),
            new AuditEventDefinition(
                key: 'content.page.deleted',
                package: 'yezzmedia/laravel-user-content',
                action: 'deleted',
                subjectType: 'content_page',
                description: 'A page was deleted.',
                severity: 'warning',
                contextKeys: ['project_id', 'page_id', 'page_title'],
            ),
            new AuditEventDefinition(
                key: 'content.redirect.created',
                package: 'yezzmedia/laravel-user-content',
                action: 'created',
                subjectType: 'content_redirect',
                description: 'A redirect rule was created.',
                severity: 'info',
                contextKeys: ['project_id', 'redirect_id', 'source', 'target'],
            ),
            new AuditEventDefinition(
                key: 'content.form.submission_received',
                package: 'yezzmedia/laravel-user-content',
                action: 'submitted',
                subjectType: 'form_submission',
                description: 'A form submission was received.',
                severity: 'info',
                contextKeys: ['form_definition_id', 'is_spam'],
            ),
        ];
    }

    public function rateLimitDefinitions(): array
    {
        return [
            new RateLimitDefinition(
                key: 'content.form.submit',
                package: 'yezzmedia/laravel-user-content',
                description: 'IP-based rate limit for public form submissions.',
                maxAttempts: 5,
                decaySeconds: 60,
                scope: 'ip',
                keyStrategy: 'ip',
            ),
        ];
    }

    public function installSteps(): array
    {
        return [
            app(PublishContentMigrationsInstallStep::class),
            app(EnsureContentStoreReadyInstallStep::class),
            app(PublishContentConfigInstallStep::class),
        ];
    }

    public function doctorChecks(): array
    {
        return [
            app(ContentStoreReadyCheck::class),
            app(OrphanedRedirectsCheck::class),
            app(PendingFormSubmissionsCheck::class),
        ];
    }
}

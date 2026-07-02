# Changelog

## v0.1.1 (2026-07-02)

- Register content addons with InstalledAddonRegistry on boot
- Wire ContentPlugin into HubExtensionRegistry for panel pages
- Fix EnsureContentStoreReadyInstallStep: `migrationsAllowed` → `allowMigrations`
- Update ContentAddonRegistrar URL generators to point directly to content pages
- Add project-context resolution and membership check to ContentBasePage
- Remove global dashboard navigation in favor of project-scoped addon links
- Set `$shouldRegisterNavigation = false` on ContentBasePage
- Add per-project sidebar navigation via DashboardPage

## v0.1.0 (2026-07-02)

- Initial package scaffold
- Pages module with slug generation, publication workflow, and navigation support
- Navigation module with menu link management and breadcrumb builder
- Redirects module with exact-match resolution, loop protection, and slug-change integration
- Forms module with field definitions, submission storage, honeypot spam protection, rate limiting, and mail delivery
- Platform package registration with install steps, doctor checks, and permissions
- 58 tests, 146 assertions

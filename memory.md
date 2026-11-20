# Project Memory — Valoduction

> **Purpose:** Persistent architectural and change log for AI agents so they do not need to re-analyze the entire repository on every task. Read this file first, and update it at the end of every completed task.

## Stack & Environment
- **Framework:** Laravel 13 (`laravel/framework: ^13.17`)
- **Admin Panel:** Filament 5 (`filament/filament: ^5.0`)
- **PHP:** `^8.3` (running on PHP 8.4)
- **App Name / URL:** `Valoduction` / `http://vd`
- **Testing:** PHPUnit 12 (`php artisan test --compact`)
- **Formatting:** Laravel Pint (`vendor/bin/pint --dirty --format agent`)
- **Strict Rule:** Never run `npm run build` (or equivalent production asset builds).

## Key Files & Architecture
- `app/Providers/Filament/AdminPanelProvider.php` — Main Filament `admin` panel (`/admin`, Amber primary color, login enabled). Uses `brandLogo` pointing to `resources/views/filament/brand.blade.php` with `Changelog::latestVersion()`.
- `resources/views/filament/brand.blade.php` — Renders the `Valoduction` brand name alongside the clickable version pill (`v1.0.0`), visible on both `/admin/login` and inside the `/admin` panel, opening `/changelog` in a new tab.
- `app/Support/Changelog.php` — Parses `changelog.md` for `latestVersion()`, `releases()`, and safe inline markdown formatting (`formatInline()`).
- `app/Http/Controllers/ChangelogController.php` — Invokable controller serving `/changelog`.
- `resources/views/changelog.blade.php` — Self-contained public changelog timeline view (light/dark mode, no Vite build dependency).
- `routes/web.php` — Defines `/` and `/changelog` (`name('changelog')`).
- `changelog.md` — Semantic versioning release log (`vMAJOR.MINOR.PATCH`).
- `AGENTS.md` & `CLAUDE.md` — Agent instructions including Laravel Boost guidelines and project workflow rules (`ntfy.sh/vd-tasks`, `memory.md`, `changelog.md`, git commit & push).

## Task History
- **2026-11-20 (`v1.0.0`):** Initialized project workflow automation (`ntfy.sh/vd-tasks` notification rule, `memory.md`, `AGENTS.md`/`CLAUDE.md` rules, automatic git commit & push), `changelog.md`, public `/changelog` timeline page, and Filament brand version pill on sign-in and panel views.

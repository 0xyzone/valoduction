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
- `app/Providers/AppServiceProvider.php` — Globally configures `CreateAction`, `EditAction`, and `ViewAction` as `7xl` slideover modals (`Width::SevenExtraLarge`).
- `app/Providers/Filament/AdminPanelProvider.php` — Main Filament `admin` panel (`/admin`, Valorant Red `#FF4655` primary, Warm Gold `#C89B3C` warning, `Plus Jakarta Sans`, collapsible sidebar, custom `App\Filament\Pages\Auth\Login`, `public/css/filament-theme.css` hook). Uses `brandLogo` pointing to `resources/views/filament/brand.blade.php` with `Changelog::latestVersion()`.
- `public/css/filament-theme.css` — Warm Valorant Glassmorphic Mobile-Card UI stylesheet (warm cream `#ECE8E1` light default, `#0F1923` dark mode, frosted glass panels/modals/cards).
- `app/Filament/Pages/Auth/Login.php` — Custom Filament 5 login page authenticating via `username` instead of `email`.
- `app/Filament/Resources/Users/` — Filament `UserResource` (`UserForm`, `UsersTable`, `ManageUsers`) managing `avatar`, `name`, `username`, `primary_phone`, `email`, `password`, and `is_active` via `7xl` slideover modals.
- `app/Console/Commands/MakeFilamentUserCommand.php` — Overrides `make:filament-user` to prompt for `username`, optional `primary-phone` and `email`, `name`, and `password`.
- `app/Models/User.php` — Implements `FilamentUser` (`canAccessPanel()` checks `is_active`) and `HasAvatar` (`getFilamentAvatarUrl()`).
- `database/migrations/2026_11_20_073426_add_profile_fields_to_users_table.php` — Adds `username` (unique), `primary_phone` (nullable), `is_active` (boolean, default `true`), `avatar` (nullable), and makes `email` nullable.
- `resources/views/filament/brand.blade.php` — Renders the `Valoduction` brand name alongside the clickable version pill (`v1.0.0`), visible on both `/admin/login` and inside the `/admin` panel, opening `/changelog` in a new tab.
- `app/Support/Changelog.php` — Parses `changelog.md` for `latestVersion()`, `releases()`, and safe inline markdown formatting (`formatInline()`).
- `app/Http/Controllers/ChangelogController.php` — Invokable controller serving `/changelog`.
- `resources/views/changelog.blade.php` — Self-contained public changelog timeline view (light/dark mode, no Vite build dependency).
- `routes/web.php` — Defines `/` and `/changelog` (`name('changelog')`).
- `changelog.md` — Semantic versioning release log (`vMAJOR.MINOR.PATCH`).
- `AGENTS.md` & `CLAUDE.md` — Agent instructions including Laravel Boost guidelines and project workflow rules (`ntfy.sh/vd-tasks`, `memory.md`, `changelog.md`, git commit & push).

## Task History
- **2026-11-20 (`v1.2.0`):** Configured global `7xl` slideover modals for Create/Edit/View actions, converted `UserResource` to `ManageUsers`, and upgraded Filament, `/changelog`, and `/` to a Warm Valorant Glassmorphic Mobile-Card UI (`#ECE8E1` / `#0F1923` + `#FF4655`).
- **2026-11-20 (`v1.1.0`):** Added `username`, `primary_phone`, `is_active`, `avatar`, and nullable `email` to `users`; built Filament `UserResource`, username-based `Login` page, `FilamentUser`/`HasAvatar` on `User`, and overrode `make:filament-user` to use `username`.
- **2026-11-20 (`v1.0.0`):** Initialized project workflow automation (`ntfy.sh/vd-tasks` notification rule, `memory.md`, `AGENTS.md`/`CLAUDE.md` rules, automatic git commit & push), `changelog.md`, public `/changelog` timeline page, and Filament brand version pill on sign-in and panel views.
